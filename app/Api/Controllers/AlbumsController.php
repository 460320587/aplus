<?php

namespace Someline\Api\Controllers;

use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Someline\Http\Requests\AlbumCreateRequest;
use Someline\Http\Requests\AlbumUpdateRequest;
use Someline\Models\Foundation\Album;
use Someline\Repositories\Interfaces\AlbumRepository;
use Someline\Validators\AlbumValidator;

class AlbumsController extends BaseController
{

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
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->repository->paginate();
    }

    public function auth_user()
    {
        return $this->repository->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AlbumCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(AlbumCreateRequest $request)
    {

        $data = $request->all();

        if (!empty($data['keywords_data'])) {
            $data['keywords'] = implode(',', $data['keywords_data']);
        } else {
            $data['keywords'] = null;
        }

        $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);

        $album = $this->repository->create($data);

        // throw exception if store failed
//        throw new StoreResourceFailedException('Failed to store.');

        // A. return 201 created
//        return $this->response->created(null);

        // B. return data
        return $album;

    }

    public function storeAudios(Request $request, $id)
    {
        $data = $request->all();

        $audio_files = $request->get('audio_files');

        if(!is_array($audio_files) || empty($audio_files)) {
            throw new StoreResourceFailedException('Empty audio files.');
        }

        return $audio_files;

        $album = Album::findOrFail($id);

        // throw exception if store failed
//        throw new StoreResourceFailedException('Failed to store.');

        // A. return 201 created
//        return $this->response->created(null);

        // B. return data
        return $album;
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
     * @return \Dingo\Api\Http\Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(AlbumUpdateRequest $request, $id)
    {

        $data = $request->all();

        if (!empty($data['keywords_data'])) {
            $data['keywords'] = implode(',', $data['keywords_data']);
        } else {
            $data['keywords'] = null;
        }
        unset($data['someline_image_url']);

        $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);

        $album = $this->repository->update($data, $id);

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
