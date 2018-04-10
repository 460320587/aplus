<?php

namespace Someline\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class UserValidator extends LaravelValidator
{

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'username' => 'required|min:3|unique:users',
            'name' => 'required',
            'role' => 'required',
            'password' => 'required|min:8',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'name' => 'required',
            'role' => 'required',
        ],
    ];

}