<?php

namespace Someline\Api\Controllers;

use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Someline\Component\Category\Api\Controllers\Traits\SomelineCategoriesControllerTrait;
use Someline\Http\Requests\AlbumCreateRequest;
use Someline\Http\Requests\AlbumUpdateRequest;
use Someline\Models\Foundation\Album;
use Someline\Models\Foundation\Audio;
use Someline\Repositories\Interfaces\AlbumRepository;
use Someline\Validators\AlbumValidator;

class AlbumsController extends BaseController
{
    use SomelineCategoriesControllerTrait;

    /**
     * @var AlbumRepository
     */
    protected $repository;

    /**
     * @var AlbumValidator
     */
    protected $validator;

    public function __construct(AlbumRepository $repository, AlbumValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = $this->getAuthUser();
        if ($user->hasRole('publisher')) {
            return $this->repository->useModel(function ($model) use ($user) {
                $model = $model->where('related_user_id', $user->getUserId());
                return $model;
            })->paginate();
        } else {
            return $this->repository->paginate();
        }
    }

    public function auth_user()
    {
        $user = $this->getAuthUser();
        return $this->repository->useModel(function ($model) use ($user) {
            if ($user->hasRole('publisher')) {
                $model = $model->where('related_user_id', $user->getUserId());
            }
            $model = $model->where('status', Album::STATUS_PUBLISHING);
            return $model;
        })->all();
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function unassigned()
    {
        return $this->repository->useModel(function ($model) {
            $model = $model->whereNull('related_user_id');
            return $model;
        })->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AlbumCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AlbumCreateRequest $request)
    {

        $data = $request->all();

        if (!empty($data['keywords_data'])) {
            $data['keywords'] = implode(',', $data['keywords_data']);
        } else {
            $data['keywords'] = null;
        }

        $this->validateSomelineCategories($data);

        $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);

        $album = $this->repository->skipPresenter(true)->create($data);

        $someline_image_ids = collect($data['images_data'])->pluck('someline_image_id')->toArray();
        $album->syncImages($someline_image_ids);

        $album = $this->updateSomelineCategories($album, $data);

        // throw exception if store failed
//        throw new StoreResourceFailedException('Failed to store.');

        // A. return 201 created
//        return $this->response->created(null);

        // B. return data
        return $this->repository->skipPresenter(false)->present($album);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     *
     * @param $albumId
     * @return \Illuminate\Http\Response
     */
    public function storeAudios(Request $request, $albumId)
    {

        $data = $request->all();

        $audio_files = $request->get('audio_files');

        if (!is_array($audio_files) || empty($audio_files)) {
            throw new StoreResourceFailedException('Empty audio files.');
        }

        /** @var Album $album */
        $album = Album::findOrFail($albumId);

        if ($album->isStatus(Album::STATUS_ENDED)) {
            throw new StoreResourceFailedException('该专辑已完结，不能再上传新声音。');
        }

        foreach ($audio_files as $key => $audio_file) {
            if (!empty($audio_file['someline_file_id'])) {
                $fileId = $audio_file['someline_file_id'];
                $data = [
                    'album_id' => $albumId,
                    'someline_file_id' => $fileId,
                ];
                $audio = Audio::where($data)->first();
                if (!$audio) {
                    $original_name = $audio_file['client_original_name'];
                    preg_match_all('!\d+!', $original_name, $matches);
                    $sequence = isset($matches[0][0]) ? $matches[0][0] : 0;
                    $name = str_replace('.mp3', '', $original_name);
                    $audio = Audio::create(array_merge($data, [
                        'name' => $name,
                        'original_name' => $original_name,
                        'audio_length' => $audio_file['duration'],
                        'audio_bitrate' => $audio_file['bitrate'],
                        'sequence' => $sequence,
                        'status' => Audio::STATUS_NOT_REVIEWED,
                    ]));

                    // update album bit rate
                    if (empty($album->audio_bitrate) && !empty($audio_file['bitrate'])) {
                        $album->audio_bitrate = $audio_file['bitrate'];
                        $album->save();
                    }
                }
            }
        }

        // update undetermined audio
        $album->audios()->where('pool', Audio::POOL_UNDETERMINED)->update([
            'pool' => Audio::POOL_LARGE,
        ]);

        /** @var Audio $lastAudio */
        $lastAudio = $album->ordered_audios('desc')->first();
        if ($lastAudio) {
            if ($lastAudio->isPool(Audio::POOL_LARGE)) {
                $lastAudio->updatePool(Audio::POOL_UNDETERMINED);
            }
        }

        // auto selected 30% for review
        $totalAudiosCount = $album->audios()->count();
        $reviewAudiosCount = $album->audios()->where('pool', Audio::POOL_REVIEW)->count();
        if ($totalAudiosCount > 0) {
            $atLeastReviewCount = round($totalAudiosCount * 0.3);
            $needReviewCount = $atLeastReviewCount - $reviewAudiosCount;
            if ($needReviewCount > 0) {
                $album->audios()->where('pool', Audio::POOL_LARGE)
                    ->inRandomOrder()
                    ->take($needReviewCount)
                    ->update([
                        'pool' => Audio::POOL_REVIEW,
                    ]);
            }
        }


        // throw exception if store failed
//        throw new StoreResourceFailedException('Failed to store.');

        // A. return 201 created
        return $this->response->created(null);

        // B. return data
//        return $album;

    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AlbumUpdateRequest $request
     * @param  string $id
     *
     * @return Response
     */
    public function update(AlbumUpdateRequest $request, $id)
    {

        $data = $request->all();

        if (!empty($data['keywords_data'])) {
            $data['keywords'] = implode(',', $data['keywords_data']);
        } else {
            $data['keywords'] = null;
        }

        $this->validateSomelineCategories($data);

        $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);

        /** @var Album $album */
        $album = $this->repository->skipPresenter(true)->update($data, $id);

        $someline_image_ids = collect($data['images_data'])->pluck('someline_image_id')->toArray();
        $album->syncImages($someline_image_ids);

        $album = $this->updateSomelineCategories($album, $data);

        // throw exception if update failed
//        throw new UpdateResourceFailedException('Failed to update.');

        // Updated, return 204 No Content
        return $this->response->noContent();

    }

    public function updateSimple(Request $request, $id)
    {

        $data = $request->all();

        $album = Album::findOrFail($id);
        $album->update($data);

        // throw exception if update failed
//        throw new UpdateResourceFailedException('Failed to update.');

        // Updated, return 204 No Content
        return $this->response->noContent();

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if ($deleted) {
            // Deleted, return 204 No Content
            return $this->response->noContent();
        } else {
            // Failed, throw exception
            throw new DeleteResourceFailedException('Failed to delete.');
        }
    }
}
