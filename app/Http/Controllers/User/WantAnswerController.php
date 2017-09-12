<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Questions;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class WantAnswerController extends Controller
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
        if (!isset($request->question_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数question_id']);
        }
        if (!$question = Questions::find($request->question_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的咨询，请确认question_id是否正确']);
        }
        if ($result = DB::table('want_answer')->where('question_id',$request->question_id)->where('user_id',$user->id)->first()) {
            return response()->json(['status' => 0,'msg' => '你已经关注了这个问题，不能重复关注']);
        }
        $result = DB::table('want_answer')->insert(['user_id' => $user->id,'question_id' => $request->question_id,'created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
        return response()->json(['status' => 1,'msg' => '操作成功，当雇主回复将会第一时间通知你']);

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
            return response()->json(['status' => 0,'msg' => '缺少参数question_id']);
        }
        if (!$question = Questions::find($id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的咨询，请确认question_id是否正确']);
        }
        $result = DB::table('want_answer')->where('question_id',$id)->where('user_id',$user->id)->delete();
        return response()->json(['status' => 1,'msg' => '操作成功，当雇主回复咨询的时候将不会通知你']);
    }

    public function checkwanted(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if (!isset($request->question_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数question_id']);
        }
        if (!$question = Questions::find($request->question_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的咨询，请确认question_id是否正确']);
        }
        $result = DB::table('want_answer')->where('question_id',$request->question_id)->where('user_id',$user->id)->count();
        if ($result > 0) {
            return response()->json(['status' => 1,'wanted' => true]);
        } else {
            return response()->json(['status' => 1,'wanted' => false]);
        }
    }
}
