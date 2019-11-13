<?php

use Illuminate\Http\Request;

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

Route::post('login', 'API\UserController@login');
Route::post('logout', 'API\UserController@logout');
Route::post('register', 'API\UserController@register');

Route::get('photos', 'API\PhotosController@index');
Route::post('photos', 'API\PhotosController@create');

Route::group(['middleware' => 'auth:api'], function(){

});
