<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 17-10-7
 * Time: 下午4:50
 */

namespace App\Notifications;

use App\Model\User;
use App\Model\Employer;
use App\Model\Works;
use App\Model\Questions;
use App\Model\Answers;
use App\Model\WorkerReviews;
use App\Model\WorkReviews;
use App\Model\WorkerReviewReply;
use App\Model\WorkReviewReply;
use Carbon\Carbon;

class Activity
{
    public $work;
    public $user;
    public $employer;
    public $question;
    public $userReview;
    public $employerReview;
    public $userReply;
    public $employerReply;
    public $followingUser;
    public $type;
    public $created_at;

    public function __construct($from_id,$to_id,$type) {
        $this->type = $type;
        switch ($type) {
            case "ue1":{
                $user = User::withCount(['finishedWorks','userFollowers','reviews'])->find($from_id);
                $this->user = $user;
                $employer = Employer::withCount('checkedWorks','reviews','userFollowers','applyingWorks')->find($to_id);
                $this->employer = $employer;
                $this->created_at = (string)Carbon::now();
                break;
            }
            case "uw1":{
                $work = Works::withCount(['questions','favoriteUser','applicants'])->find($to_id);
                $user = User::withCount(['finishedWorks','userFollowers','reviews'])->find($from_id);
                $this->user = $user;
                $this->work = $work;
                $this->created_at = (string)Carbon::now();
                break;
            }
            case "uw2":{
                $work = Works::withCount(['questions','favoriteUser','applicants'])->find($to_id);
                $user = User::withCount(['finishedWorks','userFollowers','reviews'])->find($from_id);
                $this->user = $user;
                $this->work = $work;
                $this->created_at = (string)Carbon::now();
                break;
            }
            case "uw3":{
                $work = Works::withCount(['questions','favoriteUser','applicants'])->find($to_id);
                $user = User::withCount(['finishedWorks','userFollowers','reviews'])->find($from_id);
                $this->user = $user;
                $this->work = $work;
                $this->created_at = (string)Carbon::now();
                break;
            }
            case "uu1":{
                $followingUser = User::withCount(['finishedWorks','userFollowers','reviews'])->find($to_id);
                $user = User::withCount(['finishedWorks','userFollowers','reviews'])->find($from_id);
                $this->user = $user;
                $this->$followingUser = $followingUser;
                $this->created_at = (string)Carbon::now();
                break;
            }
            case "uq1":{
                $question = Questions::withCount(['wantAnswerUsers','answers'])->find($to_id);
                $user = User::withCount(['finishedWorks','userFollowers','reviews'])->find($from_id);
                $this->user = $user;
                $work = Works::find($question->work_id);
                $this->question = $question;
                $this->work = $work;
                $this->created_at = (string)$question->created_at;
                break;
            }
            case "ur1":{
                $user = User::withCount(['finishedWorks','userFollowers','reviews'])->find($from_id);
                $this->user = $user;
                $review = WorkReviews::with(['keywords','reply'])->withCount(['useful','reply'])->find($to_id);
                if (isset($review->pic_path)) {
                    $review->pic_path = explode(',',$review->pic_path);
                } else {
                    $review->pic_path = [];
                }
                $review->total_star = number_format(($review->treat_star + $review->pay_speed + $review->description_match) / 3,1);
                $this->userReview = $review;
                $this->created_at = (string)$review->created_at;
                break;
            }
            case "ur2":{
                $user = User::withCount(['finishedWorks','userFollowers','reviews'])->find($from_id);
                $this->user = $user;
                $reply = WorkerReviewReply::find($to_id);
                $review = WorkerReviews::with(['keywords','reply'])->withCount(['useful','reply'])->find($reply->review_id);
                $this->userReply = $reply;
                if (isset($review->pic_path)) {
                    $review->pic_path = explode(',',$review->pic_path);
                } else {
                    $review->pic_path = [];
                }
                $review->total_star = number_format(($review->attitude_star + $review->ability_star + $review->description_match) / 3,1);
                $this->employerReview = $review;
                $this->created_at = (string)$reply->created_at;
                break;
            }
            case "ew1":{
                $employer = Employer::withCount('checkedWorks','reviews','userFollowers','applyingWorks')->find($from_id);
                $this->employer = $employer;
                $work = Works::withCount(['questions','favoriteUser','applicants'])->find($to_id);
                $this->work = $work;
                $this->created_at = (string)$work->created_at;
                break;
            }
            case "ew2":{
                $employer = Employer::withCount('checkedWorks','reviews','userFollowers','applyingWorks')->find($from_id);
                $this->employer = $employer;
                $work = Works::withCount(['questions','favoriteUser','applicants'])->find($to_id);
                $this->work = $work;
                $this->created_at = (string)Carbon::now();
                break;
            }
            case "eu1":{
                $employer = Employer::withCount('checkedWorks','reviews','userFollowers','applyingWorks')->find($from_id);
                $this->employer = $employer;
                $user = User::withCount(['finishedWorks','userFollowers','reviews'])->find($to_id);
                $this->user = $user;
                $this->created_at = (string)Carbon::now();
                break;
            }
            case "ea1":{
                $employer = Employer::withCount('checkedWorks','reviews','userFollowers','applyingWorks')->find($from_id);
                $this->employer = $employer;
                $answer = Answers::find($to_id);
                $question = Questions::withCount(['wantAnswerUsers','answers'])->find($answer->question_id);
                $work = Works::find($question->work_id);
                $this->answer = $answer;
                $this->question = $question;
                $this->work = $work;
                $this->created_at = (string)$answer->created_at;
                break;
            }
            case "er2":{
                $employer = Employer::withCount('checkedWorks','reviews','userFollowers','applyingWorks')->find($from_id);
                $this->employer = $employer;
                $reply = WorkReviewReply::find($to_id);
                $review = WorkReviews::with(['keywords','reply'])->withCount(['useful','reply'])->find($reply->review_id);
                $this->employerReply = $reply;
                if (isset($review->pic_path)) {
                    $review->pic_path = explode(',',$review->pic_path);
                } else {
                    $review->pic_path = [];
                }
                $review->total_star = number_format(($review->treat_star + $review->pay_speed + $review->description_match) / 3,1);
                $this->userReview = $review;
                $this->created_at = (string)$reply->created_at;
                break;
            }
        }
    }
}