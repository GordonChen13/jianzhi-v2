<?php

namespace App\Http\Controllers\User;

use App\Model\Questions;
use App\Model\User;
use App\Model\WorkerReviews;
use App\Model\WorkReviews;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Employer;
use App\Model\Answers;
use App\Model\Works;
use App\Model\WorkerReviewReply;
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
        if (!isset($request->user_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数user_id']);
        }
        if (!$user = Employer::find($request->user_id)) {
            return response()->json(['status' => 0,'msg' => '找不到该user_id对应的雇主']);
        }
        $actions = DB::table('user_action')->where('user_id',$user->id)->orderBy('created_at','desc')->paginate(15);
        foreach ($actions->items() as $action) {
            $action->user = $user;
            switch ($action->type) {
                case "ue1":{
                    $employer = Employer::withCount('checkedWorks','reviews','userFollowers','applyingWorks')->find($action->to_id);
                    $action->employer = $employer;
                    break;
                }
                case "uw1":{
                    $work = Works::withCount(['questions','favoriteUser','applicants'])->find($action->to_id);
                    $action->work = $work;
                    break;
                }
                case "uw2":{
                    $work = Works::withCount(['questions','favoriteUser','applicants'])->find($action->to_id);
                    $action->work = $work;
                    break;
                }
                case "uw3":{
                    $work = Works::withCount(['questions','favoriteUser','applicants'])->find($action->to_id);
                    $action->work = $work;
                    break;
                }
                case "uu1":{
                    $user = User::withCount(['finishedWorks','userFollowers','reviews'])->find($action->to_id);
                    $action->followingUser = $user;
                    break;
                }
                case "uq1":{
                    $question = Questions::withCount(['wantAnswerUsers','answers'])->find($action->to_id);
                    $work = Works::find($question->work_id);
                    $action->question = $question;
                    $action->work = $work;
                    break;
                }
                case "ur1":{
                    $review = WorkReviews::with(['keywords','reply'])->withCount(['useful','reply'])->find($action->to_id);
                    if (isset($review->pic_path)) {
                        $review->pic_path = explode(',',$review->pic_path);
                    } else {
                        $review->pic_path = [];
                    }
                    $review->total_star = number_format(($review->treat_star + $review->pay_speed + $review->description_match) / 3,1);
                    $action->userReview = $review;
                    break;
                }
                case "ur2":{
                    $reply = WorkerReviewReply::find($action->to_id);
                    $review = WorkerReviews::with(['keywords','reply'])->withCount(['useful','reply'])->find($reply->review_id);
                    $action->userReply = $reply;
                    if (isset($review->pic_path)) {
                        $review->pic_path = explode(',',$review->pic_path);
                    } else {
                        $review->pic_path = [];
                    }
                    $review->total_star = number_format(($review->attitude_star + $review->ability_star + $review->description_match) / 3,1);
                    $action->employerReview = $review;
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
