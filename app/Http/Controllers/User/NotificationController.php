<?php

namespace App\Http\Controllers\User;

use App\Model\Employer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UserNotificationService;
use Tymon\JWTAuth\Facades\JWTAuth;

class NotificationController extends Controller
{
    public $userNotificationService;

    public function __construct() {
        $user = JWTAuth::parseToken()->authenticate();
        $employer = Employer::find($user->id);
        $this->userNotificationService = new UserNotificationService($user);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->type) && $request->type == 'unread') {
            $notifications = $this->userNotificationService->getUnRead();
            return response()->json(['status' => 1,'notifications' => $notifications]);
        } else {
            $notifications = $this->userNotificationService->getAll();
            return response()->json(['status' => 1,'notifications' => $notifications]);
        }
    }

    public function markAsRead() {
        $result = $this->userNotificationService->markAsRead();
        return response()->json(['status' => 1,'msg' => '操作成功']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
