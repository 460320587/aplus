<?php

namespace Someline\Api\Controllers;

use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Someline\Http\Requests\AudioCreateRequest;
use Someline\Http\Requests\AudioUpdateRequest;
use Someline\Models\Foundation\Album;
use Someline\Models\Foundation\Audio;
use Someline\Repositories\Interfaces\AudioRepository;
use Someline\Validators\AudioValidator;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AudiosController extends BaseController
{

    /**
     * @var AudioRepository
     */
    protected $repository;

    /**
     * @var AudioValidator
     */
    protected $validator;

    public function __construct(AudioRepository $repository, AudioValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $user = $this->getAuthUser();
        return $this->repository->useModel(function ($model) use ($user, $data) {
            $model = $model->whereHas('album');

            if ($user->hasRole('publisher')) {
                $model = $model->where('user_id', $user->getUserId());
            } else if ($user->hasRole('reviewer')) {
                $model = $model->where('pool', Audio::POOL_REVIEW);
            }

            if (isset($data['status']) && $data['status'] !== "") {
                $model = $model->where('status', $data['status']);
            }

            if (!empty($data['album_id'])) {
                $model = $model->where('album_id', $data['album_id']);
            }

            return $model;
        })->paginate();
    }

    public function getConsumerAudios($album_id)
    {
        /** @var Album $album */
        $album = Album::findOrFail($album_id);

        $audios = $album->ordered_audios()->where('status', Audio::STATUS_APPROVED)->get();

        return $this->repository->present($audios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AudioCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AudioCreateRequest $request)
    {

        $data = $request->all();

        $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);

        $audio = $this->repository->create($data);

        // throw exception if store failed
//        throw new StoreResourceFailedException('Failed to store.');

        // A. return 201 created
//        return $this->response->created(null);

        // B. return data
        return $audio;

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
        /** @var Audio $audio */
        $audio = $this->repository->skipPresenter(true)->find($id);
        $authUser = $this->getAuthUser();
        if (!$authUser->isRoleAdmin()) {
            if (!$audio->isStatus(Audio::STATUS_NOT_REVIEWED)) {
                throw new AccessDeniedHttpException();
            }
        }
        return $this->repository->skipPresenter(false)->present($audio);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AudioUpdateRequest $request
     * @param  string $id
     *
     * @return Response
     */
    public function update(AudioUpdateRequest $request, $id)
    {

        $data = $request->all();

        if (!empty($data['name'])) {
            $original_name = $data['name'];
            preg_match_all('!\d+!', $original_name, $matches);
            $sequence = isset($matches[0][0]) ? $matches[0][0] : 0;
            $data['sequence'] = $sequence;
        }

        $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);

        $data['status'] = Audio::STATUS_NOT_REVIEWED;

        $audio = $this->repository->update($data, $id);

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
