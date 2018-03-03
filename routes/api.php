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
                $api->post('/', 'UsersController@store');
                $api->get('/me', 'UsersController@me');
                $api->get('/{id}', 'UsersController@show');
                $api->put('/{id}', 'UsersController@update');
                $api->delete('/{id}', 'UsersController@destroy');
            });

            // /albums
            $api->group(['prefix'=>'albums'],function (Router $api){
                //查
                $api->get('/','AlbumsController@index');
                //增
                $api->post('/','AlbumsController@store');
                //改
                $api->put('/{id}','AlbumsController@update')->where('id','[0-9]+');
                //删
                $api->delete('/{id}','AlbumsController@destroy')->where('id','[0,9]+');
            });
        });
        \Someline\Component\File\SomelineFileServiceProvider::api_routes($api);
        \Someline\Component\Category\SomelineCategoryServiceProvider::api_routes($api);
    });

});