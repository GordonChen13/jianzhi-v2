<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Works;
use App\Model\User;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class FavoriteWorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!isset($request->user_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数user_id']);
        }
        if (!$user = User::find($request->user_id)) {
            return response()->json(['status' => 0,'msg' => '找不到该参数对应的用户']);
        }
        $works = $user->favoriteWorks()->withCount(['applicants','favoriteUser','questions'])->orderBy('created_at','desc')->get();
        return response()->json(['status' => 1,'works' => $works]);
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
        if (!isset($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数work_id']);
        }
        if (!$work = Works::find($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的兼职，请确认work_id是否正确']);
        }
        $user->favoriteWorks()->attach($request->work_id,['created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
        return response()->json(['status' => 1,'msg' => '已经成功收藏兼职']);

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
        $user = JWTAuth::parseToken()->authenticate();
        if (!isset($id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数work_id']);
        }
        if (!$work = Works::find($id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的兼职，请确认work_id是否正确']);
        }
        $user->favoriteWorks()->detach($id);
        return response()->json(['status' => 1,'msg' => '已经取消了这个兼职的收藏']);
    }

    public function checkFavorite(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if (!isset($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数work_id']);
        }
        if (!$work = Works::find($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的兼职，请确认work_id是否正确']);
        }
        $result = DB::table('favorite_works')->where('work_id',$request->work_id)->where('user_id',$user->id)->count();
        if ($result > 0) {
            return response()->json(['status' => 1,'favorited' => true]);
        } else {
            return response()->json(['status' => 1,'favorited' => false]);
        }
    }
}
