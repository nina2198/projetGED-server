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
        Route::get('/email/{email}', 'Person\UserController@findByEmail');
        Route::post('/', 'Person\UserController@create');
        Route::delete('/{id}', 'Person\UserController@destroy');
        Route::match(['post', 'put'],'/{id}', 'Person\UserController@update');
        Route::get('/status/{id}', 'Person\UserController@instances_waiting');
        Route::get('/status/{id}', 'Person\UserController@instances_hanging');
        Route::post('/reset-password', 'Person\UserController@reinitializePassword');
        Route::get('/avatar/{user_id}', 'Person\UserController@getUserAvatar');

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
    Route::get('/track/{track_id}', 'Folder\FolderController@findByTrackId');
    Route::post('/', 'Folder\FolderController@create');
    Route::get('/archived', 'Folder\FolderController@findTreatedFolders');
    Route::get('/{id}/files', 'Folder\FolderController@findFiles');
    Route::delete('/{id}', 'Folder\FolderController@destroy');
    Route::match(['post', 'put'],'/{id}', 'Folder\FolderController@update');

    Route::group(['prefix' => 'user'], function () {
        Route::get('/{user_id}', 'Folder\FolderController@getUserFolders');
        Route::group(['prefix' => 'status'], function () {
            Route::get('/accepted/{user_id}', 'Folder\FolderController@getAcceptedFolders');
            Route::get('/pending/{user_id}', 'Folder\FolderController@getPendingFolders');
            Route::get('/rejected/{user_id}', 'Folder\FolderController@getRejectedFolders');
            Route::get('/archived/{user_id}', 'Folder\FolderController@getArchivedFolders');
        });
    });

});

Route::group(['prefix' => 'folders'], function () {

    Route::group(['prefix' => 'folder_types'], function () {
        Route::get('/', 'Folder\FolderTypeController@index');
        Route::get('/file_types', 'Folder\FolderTypeController@getAll');
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

    Route::group(['prefix' => 'file_types'], function () {
        Route::get('/', 'Folder\FileTypeController@index');
        Route::get('/{id}', 'Folder\FileTypeController@find');
        Route::post('/', 'Folder\FileTypeController@create');
        Route::delete('/{id}', 'Folder\FileTypeController@destroy');
        Route::match(['post', 'put'],'/{id}', 'Folder\FileTypeController@update');
    });

});


Route::group(['prefix' => 'activities'], function(){
    Route::get('/', 'Activity\ActivitiesController@index');
    Route::get('/j', 'Activity\ActivitiesController@join');
    Route::post('/create', 'Activity\ActivitiesController@create');
    Route::get('/{id}', 'Activity\ActivitiesController@find') ;
    Route::match(['post', 'put'], '/{id}', 'Activity\ActivitiesController@update') ;
    Route::get('/inst/{id}', 'Activity\ActivitiesController@activities_instances');
    Route::get('/service/{id}', 'Activity\ActivitiesController@service');
    Route::get('/services/{id}', 'Activity\ActivitiesController@find_service');

});

Route::group(['prefix' => 'activity_instances'], function(){
    Route::get('/', 'Activity\ActivityInstancesController@index') ;
    Route::get('/{id}', 'Activity\ActivityInstancesController@find');
    Route::get('/activity/{id}', 'Activity\ActivityInstancesController@activity');
    Route::get('/user/{id}', 'Activity\ActivityInstancesController@user'); 
    Route::get('/pourcent/{id}', 'Activity\ActivitySchemasController@getFolderProgressionPourcentage');
    Route::get('/ordre/{id}', 'Activity\ActivitySchemasController@getActivityOrderAndServiceNumber');
    Route::get('/ordr/{id}', 'Activity\ActivitySchemasController@getFolderPoucentage');


    Route::post('/init/{schema_id}/{track_id}', 'Activity\ActivityInstancesController@initialiserInstance');
    Route::get('/folder/{id}', 'Activity\ActivityInstancesController@getIdFolder');
    Route::post('/edit/{id}', 'Activity\ActivityInstancesController@edit');

});

 // Service module : 'middleware' => 'auth:api',
 Route::group(['prefix' => 'services'], function () {
    Route::get('/', 'Service\ServiceController@index');
    Route::get('/{id}', 'Service\ServiceController@find');
    Route::get('/search', 'Service\ServiceController@search');
    Route::delete('/{id}', 'Service\ServiceController@destroy');
    Route::match(['post', 'put'],'/update/{id}', 'Service\ServiceController@update');
    Route::post('/', 'Service\ServiceController@create');
    Route::get('/activities/{id}', 'Service\ServiceController@activities');
    Route::get('/u/{id}', 'Service\ServiceController@users');
});

Route::group(['prefix' => 'schemas'], function () {
    Route::post('/', 'Activity\ActivitySchemasController@create');
});
