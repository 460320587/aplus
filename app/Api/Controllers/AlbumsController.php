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
use Someline\Presenters\BasicPresenter;
use Someline\Repositories\Interfaces\AlbumRepository;
use Someline\Services\ZhangYueService;
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
                    $name = str_replace_last('.mp3', '', $original_name);
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
        $totalAudiosCount = (int)$album->audios()->count();
        $reviewAudiosCount = (int)$album->audios()->where('pool', Audio::POOL_REVIEW)->count();
        $approvedAudiosCount = (int)$album->audios()->where('pool', Audio::POOL_APPROVED)->count();
        if ($totalAudiosCount > 0) {
            $atLeastReviewCount = round($totalAudiosCount * 0.3);
            $needReviewCount = $atLeastReviewCount - $reviewAudiosCount - $approvedAudiosCount;
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

    public function getZhangYueBookInfo($book_id)
    {
        $zhangYueService = new ZhangYueService();
//        $data = $zhangYueService->fetchBookInfo('11001439');
        $data = $zhangYueService->fetchBookInfo($book_id);
//        $data = json_decode('{"partnerName": "\u6d4b\u8bd5\u4e66\u540d1", "category": "\u6742\u5fd7 -> \u638c\u9605\u51fa\u54c1\u6742\u5fd7", "displayName": "\u6d4b\u8bd5\u4e66\u540d1", "name": "\u6d4b\u8bd5\u4e66\u540d1", "bookId": 11001439, "author": "\u6d4b\u8bd5", "wordCount": 193614, "price": 0.03, "startChargeChapter": 0, "cover": "http://118.192.170.16:29998/group3/M00/0D/E5/wKgGFVdr0dGEWEMEAAAAAG9v6h8877992147.jpg", "brief": "\u9633\u6e10\u6e10\u9690\u53bb\u5148\u524d\u7684\u5149\u8f89\uff0c\u6162\u6162\u843d\u8fdb\u897f\u8fb9\u7684\u5c71\u5ce6\uff0c\u7ea2\u971e\u9f50\u5929\u3002\u9e21\u9e2d\u6210\u7fa4\u7ed3\u961f\u8fdb\u7b3c\uff0c\u725b\u9a6c\u60a0\u95f2\u81ea\u5728\u5f52\u680f\uff0c\u84dd\u8272\u7684\u708a\u70df\u8885\u8885\u5347\u817e\uff0c\u597d\u4e00\u5e45\u79cb\u65e5\u5915\u7167\u7684\u80dc\u666f\uff01\u201c\u5431\u5440\uff0c\u5431\u5440\u201d\u7684\u58f0\u97f3\u7531\u8fdc\u53ca\u8fd1\u3002\u9648\u548c\u5e73\u7684\u6bcd\u4eb2\u63a8\u7740\u6ee1\u6ee1\u4e00\u8f66\u9c9c\u5ae9\u7684\u732a\u8349\u5403\u529b\u5730\u722c\u4e0a\u5c4b\u53f0\uff0c\u201c\u5a46\u5a46\uff0c\u5e73\u4f22\u5b50\u8fd8\u6ca1\u6709\u653e\u5b66\uff1f\u201d\u201c\u4ed6\u8bf4\u4e86\uff0c\u653e\u5b66\u540e\u53bb\u780d\u67f4\u3002\u201d\u660f\u6697\u7684\u7164\u6cb9\u706f\u4e0b\uff0c\u9648\u548c\u5e73\u7684\u5a46\u5a46\u70e7\u7740\u665a\u996d\u706b\uff0c\u7076\u5802\u91cc\u7684\u706b\u5149\u6620\u7740\u5979\u7684\u6ee1\u5934\u94f6\u4e1d\u3002\u201c\u8fd9\u4e48\u665a\u4e86\u8be5\u56de\u6765\u4e86\uff0c\u8fd9\u4e2a\u4f22\u5b50\u771f\u662f\uff0c\u505a\u4ec0\u4e48\u4e8b\u90fd\u5fd8\u5f62\u3002\u201d\u9648\u548c\u5e73\u7684\u6bcd\u4eb2\u4e00\u8fb9\u5f80\u732a\u5708\u62b1\u732a\u8349\uff0c\u4e00\u8fb9\u57cb\u6028\u3002", "createTime": "2016-01-28 15:33:06", "billPattern": 20, "keywords": "\u6d4b\u8bd5,\u6742\u5fd7,\u8d2d\u7269,\u6307\u5357,\u53cc11,\u5929\u732b,\u72c2\u6b22,\u597d\u8d27", "categoryId": "1801", "completeStatus": "Y"}', true);
        return (new BasicPresenter())->present($data);
    }

}
