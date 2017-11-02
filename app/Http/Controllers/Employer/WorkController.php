<?php

namespace App\Http\Controllers\Employer;

use Illuminate\Http\Request;
use App\Model\Works;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employer = JWTAuth::parseToken()->authenticate();
        $works = Works::withCount(['applicants','favoriteUser','questions'])->where('employer_id',$employer->id);
        if (isset($request->status)) {
            $works = $works->where('status',$request->status);
        }
        if (isset($request->status_array)) {
            $works = $works->whereIn('status',$request->status_array);
        }
        if (isset($request->has_questions)) {
            $works = $works->has('questions');
        }
        $works = $works->orderBy('applicants_count','desc')->get();
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        if (!$work = Works::find($id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的兼职']);
        }
        return response()->json(['status' => 1,'work' => $work]);
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
        $employer = JWTAuth::parseToken()->authenticate();
        if (!$work = Works::find($id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的兼职']);
        }
        if ($employer->id !== $work->employer_id) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作！']);
        }
        if (isset($request->status)) {
            $work->status = $request->status;
        }
        $work->save();
        return response()->json(['status' => 1,'msg' => '修改成功']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employer = JWTAuth::parseToken()->authenticate();
        if (!$work = Works::find($id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的兼职']);
        }
        if ($employer->id !== $work->employer_id) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作！']);
        }
        $result = $work->delete();
        return response()->json(['status' => 1,'msg' => '已成功删除该兼职！']);
    }
}
