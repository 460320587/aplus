<?php

namespace Someline\Http\Controllers\Console;

use Someline\Http\Controllers\BaseController;

class UserController extends BaseController
{

    //用户列表页
    public function getUserList()
    {
        return view('console.users.list');
    }

    //新建用户页
    public function getUserNew()
    {
        return view('console.users.new');
    }

    //用户详情页
    public function getUserDetail($user_id)
    {
        return view('console.users.detail', compact('user_id'));
    }

    //用户编辑页
    public function getUserEdit($user_id)
    {
        return view('console.users.edit', compact('user_id'));
    }

}