<?php

namespace App\Http\Controllers\User;

use App\Events\User\WorkApplied;
use App\Events\User\WorkUnApplied;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Works;
use App\Model\User;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class ApplyWorkController extends Controller
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
            return response()->json(['status' => 0,'msg' => '找不到该用户']);
        }
        if (isset($request->status)) {
            $works = $user->works()->withCount(['applicants','favoriteUser','questions'])->withPivot('status')->wherePivot('status',$request->status)->orderBy('created_at','desc')->paginate(20);
        } else if (isset($request->status_array)) {
            $works = $user->works()->withCount(['applicants','favoriteUser','questions'])->withPivot('status')->wherePivotIn('status',$request->status_array)->orderBy('created_at','desc')->paginate(20);
        } else {
            $works = $user->works()->withCount(['applicants','favoriteUser','questions'])->withPivot('status')->orderBy('created_at','desc')->paginate(20);
        }
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
        $user->applyingWorks()->attach($request->work_id,['status' => 0,'created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
        event(new WorkApplied($work,$user));
        return response()->json(['status' => 1,'msg' => '已经成功申请兼职，请静候佳音']);

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
    public function update(Request $request, $user_id)
    {

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
        $result = DB::table('apply_works')->where('work_id',$id)->where('user_id',$user->id)->first();
        if ($result->status > 1) {
            return response()->json(['status' => 0,'msg' => '这个兼职已经开始了，不能直接取消，请跟雇主协商']);
        }
        $user->applyingWorks()->detach($id);
        event(new WorkUnApplied($work,$user));
        return response()->json(['status' => 1,'msg' => '已经取消了这个兼职的申请']);
    }

    public function checkApplied(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if (!isset($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数work_id']);
        }
        if (!$work = Works::find($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的兼职，请确认work_id是否正确']);
        }
        $result = DB::table('apply_works')->where('work_id',$request->work_id)->where('user_id',$user->id)->count();
        if ($result > 0) {
            return response()->json(['status' => 1,'applied' => true]);
        } else {
            return response()->json(['status' => 1,'applied' => false]);
        }
    }
}
