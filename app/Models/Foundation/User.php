<?php

namespace Someline\Models\Foundation;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Someline\Image\Models\Traits\SomelineHasImageablesTrait;
use Someline\Model\Foundation\User as BaseUser;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends BaseUser
{
    use EntrustUserTrait;
    use SomelineHasImageablesTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
        'gender', 'birthday', 'country', 'timezone', 'locale', 'username', 'phone_number', 'status',
    ];

    protected $appends = [
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * Called when model is created
     * Other events available are in BaseModelEvents
     */
    public function onCreated()
    {
        parent::onCreated();

    }

    public function getRoleAttribute()
    {
        $role = $this->roles()->first();
        if ($role) {
            return $role->name;
        } else {
            return null;
        }
    }

}
