<?php

namespace App\Http\Controllers\Employer;

use App\Model\Employer;
use App\Model\User;
use App\Model\WorkReviews;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployerController extends Controller
{
    public function __construct() {
        $this->middleware('jwt.auth')->except(['index','show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employers = Employer::withCount('checkedWorks','reviews','userFollowers','applyingWorks');
        if (isset($request->search)) {
            $employers = $employers->where('name','like','%'.$request->search.'%')->get();
            foreach ($employers as $employer) {
                $reviews = WorkReviews::where('employer_id',$employer->id)->get();
                $treat_star = number_format($reviews->avg('treat_star'),1);
                $pay_speed = number_format($reviews->avg('pay_speed'),1);
                $description_match = number_format($reviews->avg('description_match'),1);
                $total_star =  number_format(($treat_star + $pay_speed + $description_match) / 3,1);
                $employer->treat_star = (float)$treat_star;
                $employer->pay_speed = (float)$pay_speed;
                $employer->description_match = (float)$description_match;
                $employer->total_star = (float)$total_star;
            }
            return response()->json(['status' => 1,'employers'=>$employers]);
        }
        $employers =$employers->get();
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

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, $id)
    {
        $employer = Employer::withCount('checkedWorks','reviews','userFollowers','applyingWorks','thanks')->where('id',$id)->first();
        if (count($employer) == 0) {
            return response()->json(['status'=>0,'msg'=>'找不到对应的用户']);
        } else {
            $reviews = WorkReviews::where('employer_id',$employer->id)->get();
            $treat_star = number_format($reviews->avg('treat_star'),1);
            $pay_speed = number_format($reviews->avg('pay_speed'),1);
            $description_match = number_format($reviews->avg('description_match'),1);
            $total_star =  number_format(($treat_star + $pay_speed + $description_match) / 3,1);
            $employer->treat_star = (float)$treat_star;
            $employer->pay_speed = (float)$pay_speed;
            $employer->description_match = (float)$description_match;
            $employer->total_star = (float)$total_star;
            return response()->json(['status'=>1,'employer'=> $employer]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
