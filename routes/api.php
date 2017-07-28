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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register','Auth\AuthenticateController@register');
Route::post('/login','Auth\AuthenticateController@login');
Route::get('/refreshtoken','Auth\AuthenticateController@refreshToken');
Route::post('/resetPassword','Auth\AuthenticateController@resetPassword');
Route::post('/sendrandomcode','Auth\AuthenticateController@sendRandomCode');
Route::post('/resetforgottenpassword','Auth\AuthenticateController@resetForgottenPassword');
Route::resource('/tags','Common\TagsController');
Route::resource('/skills','Common\SkillController');
Route::get('/employer/{id}/works','Common\WorkController@getEmployerWorks');
Route::resource('/works','Common\WorkController');
