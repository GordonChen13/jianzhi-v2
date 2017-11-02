<?php

namespace App\Http\Controllers\Team;

use Illuminate\Http\Request;
use App\Model\Team;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Model\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        if (!isset($request->team_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数team_id']);
        }
        if (!$team = Team::find($request->team_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的团队']);
        }
        if ($result = DB::table('join_team')->where('team_id',$team->id)->where('user_id',$user->id)->where('status','>','0')->first()) {
            return response()->json(['status' => 0,'msg' => '你已经申请过了']);
        }
        DB::table('join_team')->insert(['user_id' => $user->id,'team_id' => $team->id,'status' => 0,'created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
        return response()->json(['status' => 1,'msg' => '已经向管理员发送了加入请求']);
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
        if (!isset($request->title)) {
            return response()->json(['status' => 0,'msg' => '缺少参数title']);
        }
        if (!isset($request->team_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数team_id']);
        }
        if (!$team = Team::find($request->team_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的团队']);
        }
        $owner = JWTAuth::parseToken()->authenticate();
        $teamOwner = $team->owner()->first();
        if ($owner->id !== $teamOwner->id) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作']);
        }
        if (!$result = DB::table('team_member')->where('team_id',$team->id)->where('user_id',$id)->first()) {
            return response()->json(['status' => 0,'msg' => '在队员里面找不到该用户，请确认id是否正确']);
        }
        DB::table('team_member')->where('team_id',$team->id)->where('user_id',$id)->update(['title' => $request->title]);
        return response()->json(['status' => 1,'msg' => '更改完成']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        if (!isset($request->team_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数team_id']);
        }
        if (!$team = Team::find($request->team_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的团队']);
        }
        if (isset($request->role) && $request->role == 'owner') {
            $owner = JWTAuth::parseToken()->authenticate();
            $teamOwner = $team->owner()->first();
            if ($owner->id !== $teamOwner->id) {
                return response()->json(['status' => 0,'msg' => '你无权进行该操作']);
            }
        } else {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user->id !== $id) {
                return response()->json(['status' => 0,'msg' => '你无权进行该操作']);
            }
        }
        if (!$result = DB::table('team_member')->where('team_id',$team->id)->where('user_id',$id)->first()) {
            return response()->json(['status' => 0,'msg' => '在队员里面找不到该用户，请确认id是否正确']);
        }
        DB::table('team_member')->where('team_id',$team->id)->where('user_id',$id)->delete();
        return response()->json(['status' => 1,'msg' => '操作成功']);
    }

    public function checkApplied(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        if (!isset($request->team_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数team_id']);
        }
        if (!$team = Team::find($request->team_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的团队']);
        }
        if ($result = DB::table('join_team')->where('team_id',$team->id)->where('user_id',$user->id)->where('status','>=','0')->first()) {
            return response()->json(['status' => 1,'applied' => true]);
        } else {
            return response()->json(['status' => 1,'applied' => false]);
        }
    }

    public function applyPass(Request $request) {
        if (!isset($request->user_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数user_id']);
        }
        if (!$user = User::find($request->user_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的用户']);
        }
        if (!isset($request->team_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数team_id']);
        }
        if (!$team = Team::find($request->team_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的团队']);
        }
        $owner = JWTAuth::parseToken()->authenticate();
        $teamOwner = $team->owner()->first();
        if ($owner->id !== $teamOwner->id) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作']);
        }
        if (!$result = DB::table('join_team')->where('team_id',$team->id)->where('user_id',$user->id)->first()) {
            return response()->json(['status' => 0,'msg' => '在申请列表里面找不到该用户，请确认id是否正确']);
        }
        DB::table('team_member')->insert(['team_id' => $team->id,'user_id' => $user->id,'created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
        DB::table('join_team')->where('team_id',$team->id)->where('user_id',$user->id)->delete();
        return response()->json(['status' => 1,'msg' => '操作成功']);
    }

    public function applyDeny(Request $request) {
        if (!isset($request->user_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数user_id']);
        }
        if (!$user = User::find($request->user_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的用户']);
        }
        if (!isset($request->team_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数team_id']);
        }
        if (!$team = Team::find($request->team_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的团队']);
        }
        $owner = JWTAuth::parseToken()->authenticate();
        $teamOwner = $team->owner()->first();
        if ($owner->id !== $teamOwner->id) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作']);
        }
        if (!$result = DB::table('join_team')->where('team_id',$team->id)->where('user_id',$user->id)->first()) {
            return response()->json(['status' => 0,'msg' => '在申请列表里面找不到该用户，请确认id是否正确']);
        }
        DB::table('join_team')->where('team_id',$team->id)->where('user_id',$user->id)->update(['status' => -1]);
        return response()->json(['status' => 1,'msg' => '操作成功']);
    }
}
