<?php

namespace Someline\Repositories\Eloquent;

use Someline\Repositories\Criteria\RequestCriteria;
use Someline\Repositories\Interfaces\AudioRepository;
use Someline\Models\Foundation\Audio;
use Someline\Validators\AudioValidator;
use Someline\Presenters\AudioPresenter;

/**
 * Class AudioRepositoryEloquent
 * @package namespace Someline\Repositories\Eloquent;
 */
class AudioRepositoryEloquent extends BaseRepository implements AudioRepository
{
    protected $filedSearchable = [
        'album_id' => '=',
        'album.name' => 'like',
        'audio_id' => '=',
        'name' => 'like',
    ];
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Audio::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return AudioValidator::class;
    }


    /**
    * Specify Presenter class name
    *
    * @return mixed
    */
    public function presenter()
    {

        return AudioPresenter::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
