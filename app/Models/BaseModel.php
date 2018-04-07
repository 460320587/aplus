<?php
/**
 * Created for someline-starter.
 * User: Libern
 */

namespace Someline\Models;

use Someline\Base\Models\BaseModel as Model;
use Someline\Models\Foundation\User;

class BaseModel extends Model
{
    protected $timestamp_always_save_in_utc = false;
    protected $timestamp_get_with_user_timezone = false;

    /**
     * @return User|null
     */
    public function getUser()
    {
        return parent::getUser();
    }

    /**
     * @return User|null
     */
    public function getAuthUser()
    {
        return parent::getAuthUser();
    }

    /**
     * @return User|null
     */
    public function getRelatedUser()
    {
        return $this->related_user;
    }


}