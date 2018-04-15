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
            $api->group(['prefix' => 'users', 'middleware' => ['role:root|admin']], function (Router $api) {
                $api->get('/', 'UsersController@index');
                $api->get('/all', 'UsersController@all');
                $api->post('/', 'UsersController@store');
                $api->get('/me', 'UsersController@me');
                $api->get('/{id}', 'UsersController@show');
                $api->put('/{id}', 'UsersController@update');
                $api->delete('/{id}', 'UsersController@destroy');
            });

        });

        // /zhangyue
        $api->group(['prefix' => 'zhangyue'], function (Router $api) {
            $api->get('books/{id}', 'AlbumsController@getZhangYueBookInfo');
        });


        // /consumer
        $api->group(['prefix' => 'consumer', 'middleware' => ['role:root|admin|consumer']], function (Router $api) {
            $api->get('albums', 'AlbumsController@getConsumerIndex');
            $api->get('albums/{id}', 'AlbumsController@show');
            $api->get('albums/{id}/audios', 'AudiosController@getConsumerAudios');
        });

        // /albums
        $api->group(['prefix' => 'albums'], function (Router $api) {

            $api->group(['middleware' => ['role:root|admin']], function (Router $api) {
                $api->get('/all', 'AlbumsController@all');
                $api->get('/unassigned', 'AlbumsController@unassigned');
                $api->post('/', 'AlbumsController@store');
                $api->delete('/{id}', 'AlbumsController@destroy')->where('id', '[0-9]+');
            });

            $api->group(['middleware' => ['role:root|admin|publisher']], function (Router $api) {
                $api->get('/', 'AlbumsController@index');
                $api->get('/auth_user', 'AlbumsController@auth_user');
                $api->get('/{id}', 'AlbumsController@show');
                $api->post('/{id}/audios', 'AlbumsController@storeAudios');
                $api->put('/{id}', 'AlbumsController@update')->where('id', '[0-9]+');
                $api->put('/{id}/simple', 'AlbumsController@updateSimple')->where('id', '[0-9]+');
            });
        });

        // /audios
        $api->group(['prefix' => 'audios'], function (Router $api) {
            $api->group(['middleware' => ['role:root|admin']], function (Router $api) {
                $api->get('/', 'AudiosController@index');
            });
            $api->group(['middleware' => ['role:root|admin|reviewer']], function (Router $api) {
                $api->get('/{id}', 'AudiosController@show');
            });
            $api->group(['middleware' => ['role:root|admin|publisher']], function (Router $api) {
                $api->post('/', 'AudiosController@store');
                $api->delete('/{id}', 'AudiosController@destroy')->where('id', '[0-9]+');
                $api->put('/{id}', 'AudiosController@update')->where('id', '[0-9]+');
            });
        });

        \Someline\Component\File\SomelineFileServiceProvider::api_routes($api);
        $api->group(['middleware' => ['role:root|admin|reviewer']], function (Router $api) {
            \Someline\Component\Review\SomelineReviewServiceProvider::api_routes($api);
        });
        \Someline\Component\Category\SomelineCategoryServiceProvider::api_routes($api);

    });

});