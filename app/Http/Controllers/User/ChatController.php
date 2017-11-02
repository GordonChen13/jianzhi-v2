<?php

namespace App\Http\Controllers\User;

use App\Events\User\MessageSent;
use App\Model\Message;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\ChatService;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $chatService = new ChatService($user);
        $userLists = $chatService->getUserLists();
        $messages = $chatService->getMessages();
        return response()->json(['status' => 1,'messages' => $messages,'userLists' => $userLists]);
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
        $user = JWTAuth::parseToken()->authenticate();
        if (!isset($request->target_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数target_id']);
        }
        if (!$target = User::find($request->target_id)) {
            return response()->json(['status' => 0,'msg' => '找不到该用户，请确认target_id是否有误']);
        }
        if (!isset($request->text) || empty($request->text)) {
            return response()->json(['status' => 0,'msg' => '消息内容不能为空']);
        }
        $message = new Message;
        $message->user_id = $user->id;
        $message->target_id = $target->id;
        $message->content = $request->text;
        $message->save();
        event(new MessageSent($message));
        return response()->json(['status' => 1,'msg' => '发送成功','message' => $message]);
    }

    /** 返回对应id用户的聊天记录
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $chatService = new ChatService($user);
        if (!$target = User::find($id)) {
            return response()->json(['status' => 0,'msg' => '找不到目标用户，请确认是否有误']);
        }
        $messages = $chatService->getUserMessages($id);
        return response()->json(['status' => 1,'messages' => $messages]);
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

    /** 把特定用户发来的消息标记为已读
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if (!$target = User::find($id)) {
            return response()->json(['status' => 0,'msg' => '找不到目标用户，请确认是否有误']);
        }
        $chatService = new ChatService($user);
        $result = $chatService->markAsRead($id);
        return response()->json(['status' => 1,'msg' => '操作成功']);
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
