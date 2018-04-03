<?php

namespace Someline\Api\Controllers;

use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Someline\Http\Requests\AudioCreateRequest;
use Someline\Http\Requests\AudioUpdateRequest;
use Someline\Repositories\Interfaces\AudioRepository;
use Someline\Validators\AudioValidator;

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
        return $this->repository->paginate();
    }

    /**
     * Display all resources.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function all(Request $request)
    {
        return $this->repository->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AudioCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
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
        return $this->repository->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AudioUpdateRequest $request
     * @param  string $id
     *
     * @return \Dingo\Api\Http\Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(AudioUpdateRequest $request, $id)
    {

        $data = $request->all();

        $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);

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
