<?php

namespace Someline\Repositories\Eloquent;

use Someline\Repositories\Criteria\RequestCriteria;
use Someline\Repositories\Interfaces\AlbumRepository;
use Someline\Models\Foundation\Album;
use Someline\Validators\AlbumValidator;
use Someline\Presenters\AlbumPresenter;

/**
 * Class AlbumRepositoryEloquent
 * @package namespace Someline\Repositories\Eloquent;
 */
class AlbumRepositoryEloquent extends BaseRepository implements AlbumRepository
{
    protected $fieldSearchable = [
        'album_id' => '=',
        'book_id' => '=',
        'user_id' => '=',
        'related_user_id' => '=',
        'author' => 'like',
        'keywords' => 'like',
        'name' => 'like',
    ];
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Album::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return AlbumValidator::class;
    }


    /**
    * Specify Presenter class name
    *
    * @return mixed
    */
    public function presenter()
    {

        return AlbumPresenter::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
