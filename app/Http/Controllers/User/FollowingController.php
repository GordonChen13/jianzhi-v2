<?php

namespace App\Http\Controllers\User;

use App\Events\User\Following;
use App\Events\User\UnFollowing;
use App\Model\Employer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FollowingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        if (isset($request->user_id)) {
            if (! $user = User::find($request->user_id)) {
                return response()->json(['status' => 0,'msg' => '找不到该用户']);
            }
        } else {
            $user = JWTAuth::parseToken()->authenticate();
        }
        if (!isset($request->status)) {
            return response()->json(['status' => 0,'msg' => '缺少参数status']);
        }
        $result = DB::table('follow')->where('from_id',$user->id)->where('status',$request->status)->get();
        $to_id = $result->map(function ($item) {
            return $item->to_id;
        })->toArray();
        if (in_array($request->status,['11','21','31']) ) {
            $to = User::whereIn('id',$to_id)->withCount(['finishedWorks','userFollowers','reviews'])->get();
        }
        if (in_array($request->status,['12','22','32'])) {
            $to = Employer::whereIn('id',$to_id)->withCount('checkedWorks','reviews','userFollowers','applyingWorks')->get();
        }
        return response()->json(['status' => 1,'users' => $to]);
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
            return response()->json(['status' => 0,'msg' => '抱歉，你不能关注你自己！']);
        }
        if (!isset($request->status)) {
            return response()->json(['status' => 0,'msg' => '缺少参数status']);
        }
        if ($result = DB::table('follow')->where('from_id',$user->id)->where('to_id',$request->to_id)->where('status',$request->status)->first()) {
            return response()->json(['status' => 0,'msg' => '你已经关注了他，不能重复关注']);
        }
        $user->followingUsers()->attach($request->to_id,['status' => $request->status,'created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
        event(new Following($user->id,$request->to_id,$request->status));
        return response()->json(['status' => 1,'msg' => '关注成功']);
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
        if (!isset($to_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数to_id']);
        }
        if ($user->id == $to_id) {
            return response()->json(['status' => 0,'msg' => '抱歉，你不能取消关注你自己！']);
        }
        if (!$to = User::find($to_id)) {
            return response()->json(['status' => 0,'msg' => '找不到该to_id对应的用户']);
        }
        if (!isset($request->status)) {
            return response()->json(['status' => 0,'msg' => '缺少参数status']);
        }
        if (!$result = DB::table('follow')->where('from_id',$user->id)->where('to_id',$to_id)->where('status',$request->status)->first()) {
            return response()->json(['status' => 0,'msg' => '你还没关注他，无法取消关注']);
        }
        DB::table('follow')->where('from_id',$user->id)->where('to_id',$to_id)->where('status',$request->status)->delete();
        event(new UnFollowing($user->id,$to_id,$request->status));
        return response()->json(['status' => 1,'msg' => '取消关注成功']);
    }

    public function checkFollowing(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        if (!isset($request->to_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数to_id']);
        }
        if (!isset($request->status)) {
            return response()->json(['status' => 0,'msg' => '缺少参数status']);
        }
        if ($result = DB::table('follow')->where('from_id',$user->id)->where('to_id',$request->to_id)->where('status',$request->status)->first()) {
            return response()->json(['status' => 1,'follow' => true]);
        } else {
            return response()->json(['status' => 1,'follow' => false]);
        }
    }

    public function getFollowers(Request $request) {
        if (isset($request->user_id)) {
            if (! $user = User::find($request->user_id)) {
                return response()->json(['status' => 0,'msg' => '找不到该用户']);
            }
        } else {
            $user = JWTAuth::parseToken()->authenticate();
        }
        if (!isset($request->status)) {
            return response()->json(['status' => 0,'msg' => '缺少参数status']);
        }
        $result = DB::table('follow')->where('to_id',$user->id)->where('status',$request->status)->get();
        $from_id = $result->map(function ($item) {
            return $item->from_id;
        })->toArray();
        if (in_array($request->status,['12','11','13']) ) {
            $from = User::whereIn('id',$from_id)->withCount(['finishedWorks','userFollowers','reviews'])->get();
        }
        if (in_array($request->status,['21','22','23'])) {
            $from = Employer::whereIn('id',$from_id)->withCount('checkedWorks','reviews','userFollowers','applyingWorks')->get();
        }
        return response()->json(['status' => 1,'users' => $from]);
    }
}
