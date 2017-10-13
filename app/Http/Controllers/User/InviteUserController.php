<?php

namespace App\Http\Controllers\User;

use App\Events\User\UserInvited;
use App\Model\Works;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InviteUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        if (!isset($request->to_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数to_id']);
        }
        if (!$to = User::find($request->to_id)) {
            return response()->json(['status' => 0,'msg' => '找不到该to_id对应的用户']);
        }
        if ($user->id == $request->to_id) {
            return response()->json(['status' => 0,'msg' => '抱歉，你不能邀请你自己！']);
        }
        if (!isset($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数work_id']);
        }
        if (!$work = Works::find($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '找不到该work_id对应的兼职']);
        }
        if ($result = DB::table('invite')->where('from_id',$user->id)->where('to_id',$request->to_id)->where('work_id',$request->work_id)->first()) {
            return response()->json(['status' => 0,'msg' => '你已经邀请了他，不需要重复邀请']);
        }
        $result = DB::table('invite')->insert(['from_id' => $user->id,'to_id' => $request->to_id,'work_id' => $request->work_id,'created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
        event(new UserInvited($user,$to,$work));
        return response()->json(['status' => 1,'msg' => '邀请成功']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$to_id) {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->id == $to_id) {
            return response()->json(['status' => 0,'msg' => '抱歉，你不能取消邀请你自己！']);
        }
        if (!$to = User::find($to_id)) {
            return response()->json(['status' => 0,'msg' => '找不到该to_id对应的用户']);
        }
        if (!isset($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数work_id']);
        }
        if (!$work = Works::find($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '找不到该work_id对应的兼职']);
        }
        if (!$result = DB::table('invite')->where('from_id',$user->id)->where('to_id',$to_id)->where('work_id',$request->work_id)->first()) {
            return response()->json(['status' => 0,'msg' => '你还没有邀请他，不需要取消']);
        }
        $result = DB::table('invite')->where('from_id',$user->id)->where('to_id',$to_id)->where('work_id',$request->work_id)->delete();
        return response()->json(['status' => 1,'msg' => '成功取消邀请']);
    }

    public function checkInvited(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        if (!isset($request->to_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数to_id']);
        }
        if (!$to = User::find($request->to_id)) {
            return response()->json(['status' => 0,'msg' => '找不到该to_id对应的用户']);
        }
        if (!isset($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数work_id']);
        }
        if (!$work = Works::find($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '找不到该work_id对应的兼职']);
        }
        if ($result = DB::table('invite')->where('from_id',$user->id)->where('to_id',$request->to_id)->where('work_id',$request->work_id)->first()) {
            return response()->json(['status' => 1,'invited' => true]);
        } else {
            return response()->json(['status' => 1,'invited' => false]);
        }
    }

    public function getFollowers(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        if (!isset($request->status)) {
            return response()->json(['status' => 0,'msg' => '缺少参数status']);
        }
        $result = DB::table('follow')->where('to_id',$user->id)->where('status',$request->status)->get();
        $from_id = $result->map(function ($item) {
            return $item->from_id;
        })->toArray();
        $from = User::whereIn('id',$from_id)->withCount(['finishedWorks','userFollowers','reviews'])->get();
        return response()->json(['status' => 1,'users' => $from]);
    }
}
