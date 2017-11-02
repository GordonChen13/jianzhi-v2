<?php

namespace App\Http\Controllers\Common;

use App\Events\Employer\AnswerCreated;
use App\Model\Employer;
use App\Model\Works;
use Illuminate\Http\Request;
use App\Model\Answers;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Model\Questions;
use App\Http\Controllers\Controller;

class AnswerController extends Controller
{
    public function __construct() {
        $this->middleware('jwt.auth')->only(['store','update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->question_id)) {
            $answer = Answers::where('question_id',$request->question_id)->first();
            return response()->json(['status' => 1,'answer' => $answer]);
        } else {
            return response()->json(['status' => 0,'msg' => '缺少参数question_id']);
        }
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
        $employer = JWTAuth::parseToken()->authenticate();
        if (!isset($request->text)) {
            return response()->json(['status' => 0,'msg' => '回复内容不能为空']);
        } elseif (!isset($request->question_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数question_id']);
        } else {
            if (!$question = Questions::find($request->question_id)) {
                return response()->json(['status' => 0,'msg' => '找不到该question_id对应的问题']);
            }
            if ($employer->id !== $question->work()->employer_id) {
                return response()->json(['status' => 0,'msg' => '你无权回复该咨询！']);
            }
            if ( $answer =Answers::create([
                'question_id' => $request->question_id,
                'employer_id' => $employer->id,
                'content' => $request->text
            ])) {
                $work = Works::find($question->work_id);
                $employer = Employer::find($employer->id);
                event(new AnswerCreated($answer,$work,$employer));
                return response()->json(['status' => 1,'answer' => $answer]);
            } else {
                return response()->json(['status' => 0,'msg' => '保存问题时出错，请稍后再试']);
            }
        }
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
