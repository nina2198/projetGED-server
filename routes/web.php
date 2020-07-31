<?php

use Illuminate\Support\Facades\Route;
use App\Models\Service\Service;

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

Route::get('/', function () {
    $service = factory('App\Models\Service\Service',1)->create();
    dd($service->nomService) ;
    return view('welcome');
});
