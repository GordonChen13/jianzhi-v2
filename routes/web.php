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

Route::get('/', function () {
    return view('welcome');
});
Route::post('/register','Auth\AuthenticateController@register');
Route::post('/login','Auth\AuthenticateController@login');
Route::post('/resetPassword','Auth\AuthenticateController@resetPassword');
Route::post('/sendrandomcode','Auth\AuthenticateController@sendRandomCode');
Route::post('/resetforgottenpassword','Auth\AuthenticateController@resetForgottenPassword');
