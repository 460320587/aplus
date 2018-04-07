<?php

use Illuminate\Http\Request;
use Dingo\Api\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// v1
$api->version('v1', [
    'namespace' => 'Someline\Api\Controllers',
    'middleware' => ['api']
], function (Router $api) {

    $api->group(['middleware' => ['auth:api']], function (Router $api) {

        // Rate: 100 requests per 5 minutes
        $api->group(['middleware' => ['api.throttle'], 'limit' => 100, 'expires' => 5], function (Router $api) {

            // /users
            $api->group(['prefix' => 'users'], function (Router $api) {
                $api->get('/', 'UsersController@index');
                $api->get('/all', 'UsersController@all');
                $api->post('/', 'UsersController@store');
                $api->get('/me', 'UsersController@me');
                $api->get('/{id}', 'UsersController@show');
                $api->put('/{id}', 'UsersController@update');
                $api->delete('/{id}', 'UsersController@destroy');
            });
        });

        // /albums
        $api->group(['prefix' => 'albums'], function (Router $api) {
            //查
            $api->get('/', 'AlbumsController@index');
            //当前登陆用户
            $api->get('/auth_user', 'AlbumsController@auth_user');
            //专辑分配
            $api->get('/unassigned', 'AlbumsController@unassigned');
            //增
            $api->post('/', 'AlbumsController@store');
            //查 专辑详情页
            $api->get('/{id}', 'AlbumsController@show');
            //修改声音
            $api->post('/{id}/audios', 'AlbumsController@storeAudios');
            //改
            $api->put('/{id}', 'AlbumsController@update')->where('id', '[0-9]+');
            //修改分配专辑
            $api->put('/{id}/simple', 'AlbumsController@updateSimple')->where('id', '[0-9]+');
            //删
            $api->delete('/{id}', 'AlbumsController@destroy')->where('id', '[0,9]+');
        });

        //audios
        $api->group(['prefix' => 'audios'], function (Router $api) {
            //查
            $api->get('/', 'AudiosController@index');
            //增
            $api->post('/', 'AudiosController@store');
            //查 专辑详情页
            $api->get('/{id}', 'AudiosController@show');
            //改
            $api->put('/{id}', 'AudiosController@update')->where('id', '[0-9]+');
            //删
            $api->delete('/{id}', 'AudiosController@destroy')->where('id', '[0,9]+');
        });

        \Someline\Component\File\SomelineFileServiceProvider::api_routes($api);
        \Someline\Component\Review\SomelineReviewServiceProvider::api_routes($api);
        \Someline\Component\Category\SomelineCategoryServiceProvider::api_routes($api);
    });

});