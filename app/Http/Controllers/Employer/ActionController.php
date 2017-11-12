<?php

namespace App\Http\Controllers\Employer;

use App\Model\Questions;
use App\Model\User;
use App\Model\WorkReviews;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Employer;
use App\Model\Answers;
use App\Model\Works;
use App\Model\WorkReviewReply;
use Illuminate\Support\Facades\DB;

class ActionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!isset($request->employer_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数employer_id']);
        }
        if (!$employer = Employer::find($request->employer_id)) {
            return response()->json(['status' => 0,'msg' => '找不到该employer_id对应的雇主']);
        }
        $actions = DB::table('employer_action')->where('employer_id',$employer->id)->orderBy('created_at','desc')->paginate(15);
        foreach ($actions->items() as $action) {
            switch ($action->type) {
                case "ew1":{
                    $work = Works::withCount(['questions','favoriteUser','applicants'])->find($action->to_id);
                    $action->work = $work;
                    break;
                }
                case "ew2":{
                    $work = Works::withCount(['questions','favoriteUser','applicants'])->find($action->to_id);
                    $action->work = $work;
                    break;
                }
                case "eu1":{
                    $user = User::withCount(['finishedWorks','userFollowers','reviews'])->find($action->to_id);
                    $action->user = $user;
                    break;
                }
                case "ea1":{
                    $answer = Answers::find($action->to_id);
                    $question = Questions::withCount(['wantAnswerUsers','answers'])->find($answer->question_id);
                    $work = Works::find($question->work_id);
                    $action->answer = $answer;
                    $action->question = $question;
                    $action->work = $work;
                    break;
                }
                case "er1":{
                    $review = WorkerReviews::with(['keywords','reply'])->withCount(['useful','reply'])->find($action->to_id);
                    if (isset($review->pic_path)) {
                        $review->pic_path = explode(',',$review->pic_path);
                    } else {
                        $review->pic_path = [];
                    }
                    $review->total_star = number_format(($review->treat_star + $review->pay_speed + $review->description_match) / 3,1);
                    $action->employerReview = $review;
                    break;
                }
                case "er2":{
                    $reply = WorkReviewReply::find($action->to_id);
                    $review = WorkReviews::with(['keywords','reply'])->withCount(['useful','reply'])->find($reply->review_id);
                    $action->employerReply = $reply;
                    if (isset($review->pic_path)) {
                        $review->pic_path = explode(',',$review->pic_path);
                    } else {
                        $review->pic_path = [];
                    }
                    $review->total_star = number_format(($review->treat_star + $review->pay_speed + $review->description_match) / 3,1);
                    $action->userReview = $review;
                    break;
                }
            }
        }
        return response()->json(['status' => 1,'feeds' => $actions]);
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
