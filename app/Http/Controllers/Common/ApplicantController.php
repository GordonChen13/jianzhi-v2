<?php

namespace App\Http\Controllers\Common;

use App\Events\Employer\PassApply;
use App\Model\User;
use Illuminate\Http\Request;
use App\Model\Works;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use App\Events\Employer\DenyApply;

class ApplicantController extends Controller
{
    public function __construct() {
        $this->middleware('jwt.auth')->only(['store','update','delete']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$work_id)
    {
        if (!$work = Works::find($work_id)) {
            return response()->json(['status' =>0,'msg' => '找不到该兼职']);
        };
        $applicants = $work->applicants()->withCount(['finishedWorks','userFollowers','reviews'])->get();
        return response()->json(['status' => 1,'applicants' => $applicants]);
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
    public function show($work_id,$id)
    {
        if (!$work = Works::find($work_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的兼职，请检查work_id是否正确']);
        }
        if (!$user = User::find($id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的用户，请检查user_id是否正确']);
        }
        if (!$privot = DB::table('apply_works')->where('work_id',$work->id)->where('user_id',$user->id)->first()) {
            return response()->json(['status' => 0,'msg' => '找不到对应的申请记录，请检查参数是否正确']);
        }
        $status = $privot->status;
        return response()->json(['status' => 1,'applyStatus' => $status]);

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
    public function update(Request $request, $work_id,$id)
    {
        $employer =JWTAuth::parseToken()->authenticate();
        if (!$work = Works::find($work_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的兼职，请检查work_id是否正确']);
        }
        if (!$user = User::find($id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的用户，请检查user_id是否正确']);
        }
        if ($employer->id !== $work->employer_id) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作！']);
        }
        if (!isset($request->result)) {
            return response()->json(['status' => 0,'msg' => '缺少参数result']);
        }
        if ($request->result == 'pass') {
            if ($work->need_num == $work->hired_num) {
                return response()->json(['status' => 0,'msg' => '该兼职通过申请的人数已经达到上限，如需要更多人数，请去修改需要人数']);
            }
            if ($work->need_interview) {
                $work->applicants()->updateExistingPivot($user->id,['status' => 1]);
            } else {
                $work->applicants()->updateExistingPivot($user->id,['status' => 2]);
            }
            $work->hired_num = $work->hired_num + 1;
            $work->save();
            event(new PassApply($work,$user));
        } else {
            $work->applicants()->updateExistingPivot($user->id,['status' => -1]);
            event(new DenyApply($work,$user));
        }
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
