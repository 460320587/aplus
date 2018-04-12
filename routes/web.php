<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth Routes
Auth::routes();

// Basic Routes
//Route::get('/home', 'HomeController@index');

// Protected Routes
Route::group(['middleware' => 'auth'], function () {

    Route::get('/', function () {
        return redirect('console');
    });

//    Route::get('/', 'ExampleController@getIndexExample');
    Route::get('blank-example', 'ExampleController@getBlankExample');
    Route::get('desktop-example', 'ExampleController@getDesktopExample');

    Route::get('users', 'UserController@getUserList');

});

// Mobile Routes
Route::group(['prefix' => 'm', 'namespace' => 'Mobile'], function () {

    // Mobile App
    Route::get('/', 'MobileController@getIndex');

    Route::get('/app', 'MobileController@getApp');

    // Protected Routes
    Route::group(['middleware' => 'auth'], function () {

        Route::get('users', 'UserController@getUserList');

        Route::get('users/{id}', 'UserController@getUserDetail');

    });

});

// Console Routes
Route::group(['prefix' => 'console', 'middleware' => 'auth', 'namespace' => 'Console'], function () {

    Route::get('/', function () {
        return redirect('console/albums');
    });

//    Route::get('/', 'ConsoleController@getConsoleHome');
    Route::get('oauth', 'ConsoleController@getOauth');
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'UserController@getUserList');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'UserController@getUserList');
        Route::get('/new', 'UserController@getUserNew');
        Route::get('/{id}', 'UserController@getUserDetail');
        Route::get('/{id}/edit', 'UserController@getUserEdit');
    });

    Route::group(['prefix' => 'albums'], function () {
        Route::get('/', 'AlbumController@getAlbumList');
        Route::get('/new', 'AlbumController@getAlbumNew');
        Route::get('/categories', 'AlbumController@getAlbumCategory');
        Route::get('/assign', 'AlbumController@getAlbumAssign');
        Route::get('/{id}', 'AlbumController@getAlbumDetail');
        Route::get('/{id}/edit', 'AlbumController@getAlbumEdit');
        Route::get('/{id}/assign', 'AlbumController@getAlbumSingleAssign');
        Route::get('/{id}/audios', 'AlbumController@getAlbumAudios');
        Route::get('/{id}/audios/upload', 'AlbumController@getAlbumAudiosUpload');
    });

    Route::group(['prefix' => 'audios'], function () {
        Route::get('/', 'AudioController@getAudioList');
        Route::get('/new', 'AudioController@getAudioNew');
        Route::get('/review', 'AudioController@getAudioRandomReview');
        Route::get('/categories', 'AudioController@getAudioCategory');
        Route::get('/{id}', 'AudioController@getAudioDetail');
        Route::get('/{id}/edit', 'AudioController@getAudioEdit');
        Route::get('/{id}/review', 'AudioController@getAudioReview');
    });

    Route::group(['prefix' => 'someline'], function () {
        Route::get('/form', function () {
            return view('console.someline.form');
        });
    });

});

// Image Routes
// @WARNING: The 'image' prefix is reserved for SomelineImageService
Route::group(['prefix' => 'image'], function () {

    Route::group(['middleware' => 'auth'], function () {
        Route::post('/', 'ImageController@postImage');
        Route::post('/wang_image', 'ImageController@postWangEditorImage');
    });

    Route::get('{type}/{name}', 'ImageController@showTypeImage');
    Route::get('/{name}', 'ImageController@showOriginalImage');

});

// File Routes
// @WARNING: The 'file' prefix is reserved for SomelineFileService
Route::group(['prefix' => 'file'], function () {

    Route::group(['middleware' => 'auth'], function () {
        Route::post('/', 'FileController@postFile');
    });

});

// Locale Routes
// @WARNING: The 'locales' prefix is reserved for SomelineLocaleController
Route::group(['prefix' => 'locales'], function () {

    Route::get('/{locale}.js', '\Someline\Support\Controllers\LocaleController@getLocaleJs');

    Route::get('/switch/{locale}', '\Someline\Support\Controllers\LocaleController@getSwitchLocale');

});