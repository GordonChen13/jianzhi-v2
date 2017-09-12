<?php

use Illuminate\Http\Request;

Route::namespace('Auth')->group(function () {
    Route::post('/register','AuthenticateController@register');
    Route::post('/login','AuthenticateController@login');
    Route::get('/refreshtoken','AuthenticateController@refreshToken');
    Route::post('/resetPassword','AuthenticateController@resetPassword');
    Route::post('/sendrandomcode','AuthenticateController@sendRandomCode');
    Route::post('/resetforgottenpassword','AuthenticateController@resetForgottenPassword');
});
Route::resource('/employers','Employer\EmployerController');
Route::resource('/users','User\UserController');
Route::namespace('Common')->group(function () {
    Route::resource('/tags','TagsController');
    Route::resource('/skills','SkillController');
    Route::get('/employer/{id}/works','WorkController@getEmployerWorks');
    Route::resource('/works','WorkController');
    Route::post('/photos/avatar','PhotoController@updateUserAvatar');
    Route::post('/photos/cover','PhotoController@updateUserCover');
    Route::resource('/photos','PhotoController');
    Route::resource('/questions','QuestionController');
    Route::resource('/answers','AnswerController');
});
Route::namespace('User')->prefix('user')->middleware('jwt.auth')->group(function () {
    Route::get('/appliedstatus','ApplyWorkController@checkApplied');
    Route::resource('/works','ApplyWorkController');
    Route::get('/favoritestatus','FavoriteWorkController@checkFavorite');
    Route::resource('/favoriteworks','FavoriteWorkController');
    Route::get('/followers','FollowingController@getFollowers');
    Route::get('/followingcheck','FollowingController@checkFollowing');
    Route::resource('/followings','FollowingController');
    Route::resource('/report','ReportController');
    Route::resource('/advice','AdviceController');
    Route::get('/wantedcheck','WantAnswerController@checkwanted');
    Route::resource('/wantanswer','WantAnswerController');
    Route::get('/invitedcheck','InviteUserController@checkInvited');
    Route::resource('/inviteuser','InviteUserController');
});
Route::namespace('Employer')->prefix('employer')->middleware('jwt.auth')->group(function () {
    Route::resource('/works','WorkController');
});