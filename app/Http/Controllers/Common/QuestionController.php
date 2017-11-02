<?php

namespace App\Http\Controllers\Common;

use App\Events\User\QuestionCreated;
use App\Model\Works;
use Illuminate\Http\Request;
use App\Model\Questions;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class QuestionController extends Controller
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
        if (!isset($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数work_id']);
        } else {
            $questions = Questions::withCount(['wantAnswerUsers','answers'])->where('work_id',$request->work_id);
        }
        if (isset($request->order)) {
            if ($request->order == 'newest') {
                $questions->orderBy('created_at','desc');
            } else if ($request->order == 'count') {
                $questions->orderBy('want_answer_users_count','desc');
            }
        } else {
            $questions->orderBy('want_answer_users_count','desc');
        }
        if (isset($request->has_answers)) {
            $questions = $questions->has('answers');
        }
        $questions = $questions->get();
        return response()->json(['status' => 1,'questions' => $questions]);
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
        if (!isset($request->text)) {
            return response()->json(['status' => 0,'msg' => '咨询内容不能为空']);
        } elseif (!isset($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数work_id']);
        } else {
            if(!$work = Works::find($request->work_id)) {
                return response()->json(['status' => 0,'msg' => '找不到对应的兼职']);
            }
            if ( $question =Questions::create([
                'work_id' => $request->work_id,
                'user_id' => $user->id,
                'content' => $request->text
            ])) {
                event(new QuestionCreated($question,$work,$user));
                return response()->json(['status' => 1,'question' => $question]);
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
