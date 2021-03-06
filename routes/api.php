<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::pattern('id', '[0-9]+');

Route::group(['prefix' => 'auth'], function () {

    Route::post('token', 'AuthController@login');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', 'AuthController@user');
        Route::delete('token', 'AuthController@logout');
        Route::get('permissions', 'AuthController@permissions');
        Route::get('roles', 'AuthController@roles');
       
    });
});
Route::group(['prefix' => 'persons'], function () {

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'Person\UserController@index');
        Route::get('/{id}', 'Person\UserController@find');
        Route::post('/', 'Person\UserController@create');
        Route::delete('/{id}', 'Person\UserController@destroy');
        Route::match(['post', 'put'],'/{id}', 'Person\UserController@update');
    });

    Route::group(['prefix' => 'permissions'], function () {
        Route::get('/', 'Person\PermissionController@index');
        Route::get('/{id}', 'Person\PermissionController@find');
        Route::post('/', 'Person\PermissionController@create');
        Route::delete('/{id}', 'Person\PermissionController@destroy');
    });

    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', 'Person\RoleController@index');
        Route::get('/{id}', 'Person\RoleController@find');
        Route::post('/', 'Person\RoleController@create');
        Route::delete('/{id}', 'Person\RoleController@destroy');
        Route::match(['post', 'put'],'/{id}', 'Person\RoleController@update');
    });

    Route::group(['prefix' => 'permission-user'], function () {
        Route::get('/{user_id}/{permission_id}', 'Person\PermissionUserController@find');
        Route::post('/', 'Person\PermissionUserController@create');
    });

    Route::group(['prefix' => 'permission-role'], function () {
        Route::get('/{permission_id}/{role_id}', 'Person\PermissionRoleController@find');
        Route::post('/', 'Person\PermissionRoleController@create');
    });

   Route::group(['prefix' => 'role-user'], function () {
        Route::get('/{user_id}/{role_id}', 'Person\RoleUserController@find');
        Route::post('/', 'Person\RoleUserController@create');
    });

});

Route::group(['prefix' => 'folders'], function () {

    Route::get('/', 'Folder\FolderController@index');
    Route::get('/{id}', 'Folder\FolderController@find');
    Route::post('/', 'Folder\FolderController@create');
    Route::delete('/{id}', 'Folder\FolderController@destroy');
    Route::match(['post', 'put'],'/{id}', 'Folder\FolderController@update');

});

Route::group(['prefix' => 'folders'], function () {

    Route::group(['prefix' => 'folder_types'], function () {
        Route::get('/', 'Folder\FolderTypeController@index');
        Route::get('/{id}', 'Folder\FolderTypeController@find');
        Route::post('/', 'Folder\FolderTypeController@create');
        Route::delete('/{id}', 'Folder\FolderTypeController@destroy');
        Route::match(['post', 'put'],'/{id}', 'Folder\FolderTypeController@update');
    });

    Route::group(['prefix' => 'files'], function () {
        Route::get('/', 'Folder\FileController@index');
        Route::get('/{id}', 'Folder\FileController@find');
        Route::post('/', 'Folder\FileController@create');
        Route::delete('/{id}', 'Folder\FileController@destroy');
        Route::match(['post', 'put'],'/{id}', 'Folder\FileController@update');
    });

});
