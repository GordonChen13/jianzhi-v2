<?php

use Illuminate\Http\Request;
Route::get('/test','TestController@test');

Route::namespace('Auth')->group(function () {
    Route::post('/register','AuthenticateController@register');
    Route::post('/login','AuthenticateController@login');
    Route::get('/logout','AuthenticateController@logout');
    Route::get('/refreshtoken','AuthenticateController@refreshToken');
    Route::post('/resetPassword','AuthenticateController@resetPassword');
    Route::post('/sendrandomcode','AuthenticateController@sendRandomCode');
    Route::post('/resetforgottenpassword','AuthenticateController@resetForgottenPassword');
});

Route::namespace('Common')->group(function () {
    Route::resource('/tags','TagsController');
    Route::resource('/skills','SkillController');
    Route::get('/employer/{id}/works','WorkController@getEmployerWorks');
    Route::resource('/works','WorkController');
    Route::resource('/work/{work_id}/applicant','ApplicantController');
    Route::post('/photos/avatar','PhotoController@updateUserAvatar');
    Route::post('/photos/cover','PhotoController@updateUserCover');
    Route::resource('/photos','PhotoController');
    Route::resource('/questions','QuestionController');
    Route::resource('/answers','AnswerController');
});

Route::namespace('User')->group(function () {
    Route::resource('/users','UserController',['except' => ['index','show']]);
    Route::get('/user/actions','ActionController@index');
    Route::get('/users','UserController@index');
    Route::get('/users/{id}','UserController@show');
    Route::get('/user/works','ApplyWorkController@index');
    Route::get('/user/works','ApplyWorkController@index');
    Route::get('/user/works/{id}','ReviewController@show');
    Route::get('/user/reviews','ReviewController@index');
    Route::get('/user/review/reply','ReplyController@index');
    Route::get('/user/review/keywords','ReviewKeywordsController@index');
    Route::get('/user/favoriteworks','FavoriteWorkController@index');
    Route::resource('/user','UserController',['except' => ['index','show']]);
    Route::middleware('jwt.auth')->prefix('user')->group(function (){
        Route::get('/appliedstatus','ApplyWorkController@checkApplied');
        Route::resource('/works','ApplyWorkController',['except' => ['index','show']]);
        Route::get('/favoritestatus','FavoriteWorkController@checkFavorite');
        Route::resource('/favoriteworks','FavoriteWorkController',['except' => ['index','show']]);
        Route::get('/followers','FollowingController@getFollowers');
        Route::get('/followingcheck','FollowingController@checkFollowing');
        Route::resource('/followings','FollowingController');
        Route::resource('/report','ReportController');
        Route::resource('/advice','AdviceController');
        Route::get('/wantedcheck','WantAnswerController@checkwanted');
        Route::resource('/wantanswer','WantAnswerController');
        Route::get('/invitedcheck','InviteUserController@checkInvited');
        Route::resource('/inviteuser','InviteUserController');
        Route::post('/review/picture','ReviewController@storeReviewPicture');
        Route::get('/review/status','ReviewController@checkReviewed');
        Route::resource('/reviews','ReviewController',['except' => ['index']]);
        Route::resource('/review/reply','ReplyController',['except' => ['index','show']]);
        Route::resource('/feeds','FeedController');
        Route::get('/notifications/markasread','NotificationController@markAsRead');
        Route::resource('/notifications','NotificationController');
        Route::resource('/chat','ChatController');
    });
});

Route::namespace('Employer')->group(function () {
    Route::resource('/employer','EmployerController',['except' => ['index','show']]);
    Route::get('/employer/actions','ActionController@index');
    Route::get('/employers','EmployerController@index');
    Route::get('/employers/{id}','EmployerController@show');
    Route::get('employer/reviews','ReviewController@index');
    Route::get('/employer/review/keywords','ReviewKeywordsController@index');
    Route::get('employer/review/reply','ReplyController@index');
    Route::middleware('jwt.auth')->prefix('employer')->group(function () {
        Route::resource('/works','WorkController');
        Route::post('/review/picture','ReviewController@storeReviewPicture');
        Route::get('/review/status','ReviewController@checkReviewed');
        Route::post('/review/useful','ReviewController@useful');
        Route::resource('/reviews','ReviewController',['except' => ['index']]);
        Route::resource('/review/reply','ReplyController',['except' => ['index','show']]);
        Route::get('/notifications/markasread','NotificationController@markAsRead');
        Route::resource('/notifications','NotificationController');
    });
});

Route::namespace('Company')->group(function () {
    Route::resource('/company','CompanyController');
    Route::middleware('jwt.auth')->prefix('company')->group(function () {
        Route::post('/logo','CompanyController@updateLogo');
        Route::post('/picture','CompanyController@storePicture');
        Route::post('/licence','CompanyController@storeLicence');
        Route::delete('/{company_id}/picture','CompanyController@deletePicture');
        Route::get('/member/appliedstatus','MemberController@checkApplied');
        Route::post('/member/applypass','MemberController@applyPass');
        Route::post('/member/applydeny','MemberController@applyDeny');
        Route::resource('/member','MemberController');
    });
});

Route::namespace('Team')->group(function () {
    Route::resource('/team','TeamController');
    Route::middleware('jwt.auth')->prefix('team')->group(function () {
        Route::post('/logo','TeamController@updateLogo');
        Route::post('/picture','TeamController@storePicture');
        Route::delete('/{team_id}/picture','TeamController@deletePicture');
        Route::get('/member/appliedstatus','MemberController@checkApplied');
        Route::resource('/member','MemberController');
    });
});