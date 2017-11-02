<?php

namespace App\Http\Controllers\Employer;

use App\Events\User\WorkReviewed;
use App\Model\Works;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Model\WorkReviews;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
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
        if (! $employer = User::find($request->employer_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的用户，请确认employer_id是否正确']);
        }
        if (isset($request->total_star)) {
            $reviews = WorkReviews::where('employer_id',$request->employer_id)->get();
            $treat_star = number_format($reviews->avg('treat_star'),1);
            $pay_speed = number_format($reviews->avg('pay_speed'),1);
            $description_match = number_format($reviews->avg('description_match'),1);
            $total_star =  number_format(($treat_star + $pay_speed + $description_match) / 3,1);
            return response()->json(['status' => 1,'total_star' => (float)$total_star,'treat_star' => (float)$treat_star,
                'pay_speed' => (float)$pay_speed,'description_match' => (float)$description_match]);
        }
        $reviews = WorkReviews::with(['keywords','reply'])->withCount(['useful','reply'])->where('employer_id',$request->employer_id);
        $reviews = $reviews->orderBy('created_at','desc')->get();
        foreach ($reviews as $review) {
            if (isset($review->pic_path)) {
                $review->pic_path = explode(',',$review->pic_path);
            } else {
                $review->pic_path = [];
            }
            $review->total_star = number_format(($review->treat_star + $review->pay_speed + $review->description_match) / 3,1);
        }
        return response()->json(['status' => 1,'reviews' => $reviews]);
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
            return response()->json(['status' => 0,'msg' => '缺少参数work_id'],400);
        }
        if (!$work = Works::find($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的兼职'],404);
        }
        if (!$result = DB::table('apply_works')->where('user_id',$user->id)->where('work_id',$work->id)->where('status','>',1)->first()) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作！'],400);
        }
        if (!isset($request->employer_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数employer_id'],400);
        }
        if (!$employer = User::find($request->employer_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的用户'],404);
        }
        if ($employer->id !== $work->employer_id) {
            return response()->json(['status' => 0,'msg' => '参数employer_id不是work_id对应兼职的发布者id！'],403);
        }
        if (!isset($request->treat_star)) {
            return response()->json(['status' => 0,'msg' => '缺少参数treat_star'],400);
        }
        if (!isset($request->pay_speed)) {
            return response()->json(['status' => 0,'msg' => '缺少参数pay_speed'],400);
        }
        if (!isset($request->description_match)) {
            return response()->json(['status' => 0,'msg' => '缺少参数description_match'],400);
        }
        if (!isset($request->text)) {
            return response()->json(['status' => 0,'msg' => '缺少参数text']);
        }
        if ($review = WorkReviews::where('employer_id',$employer->id)->where('work_id',$work->id)->where('user_id',$user->id)->first()) {
            return response()->json(['status' => 0,'msg' => '你已经评价过了，不能重复评价']);
        }
        $review = new WorkReviews();
        $review->employer_id = $employer->id;
        $review->work_id = $work->id;
        $review->user_id = $user->id;
        $review->treat_star = $request->treat_star;
        $review->pay_speed = $request->pay_speed;
        $review->description_match = $request->description_match;
        $review->content = $request->text;
        $review->save();
        if (isset($request->keywords)) {
            $review->keywords()->attach($request->keywords,['created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
        }
        if (isset($request->isThanks) && $request->isThanks == true) {
            DB::table('thanks_employer')->insert(['employer_id' => $employer->id,'user_id' => $user->id,
                'created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
        }
        $user->user_exp += $work->pay_amount;
        $user->save();
        event(new WorkReviewed($review));
        return response()->json(['status' => 1,'msg' => '评分成功']);
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

    public function storeReviewPicture(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        if (!isset($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数work_id'],400);
        }
        if (!$work = Works::find($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的兼职'],404);
        }
        if (!$result = DB::table('apply_works')->where('user_id',$user->id)->where('work_id',$work->id)->where('status','>',1)->first()) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作！'],400);
        }
        if (!isset($request->employer_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数employer_id'],400);
        }
        if (!$employer = User::find($request->employer_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的用户'],404);
        }
        if ($employer->id !== $work->employer_id) {
            return response()->json(['status' => 0,'msg' => '参数employer_id不是work_id对应兼职的发布者id！'],403);
        }
        if (!$review = WorkReviews::where('employer_id',$employer->id)->where('work_id',$work->id)->where('user_id',$user->id)->first()) {
            return response()->json(['status' => 0,'msg' => '找不到对应的评价'],404);
        }
        $oldPicPath = $review->pic_path;
        $picture = $request->picture;
        if (isset($request->picture)) {
            $picPath = $picture->store('images');
            if (!$oldPicPath) {
                $newPicPath = $picPath;
            } else {
                $newPicPath = $oldPicPath.','.$picPath;
            }
        } else {
            $newPicPath = null;
        }

        $review->pic_path = $newPicPath;
        $review->save();
        return response()->json(['status' =>1,'msg' => '上传成功']);
    }

    public function checkReviewed(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        if (!isset($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数work_id'],400);
        }
        if (!$work = Works::find($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的兼职'],404);
        }
        if (!$result = DB::table('apply_works')->where('user_id',$user->id)->where('work_id',$work->id)->where('status','>',1)->first()) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作！'],400);
        }
        if (!isset($request->employer_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数employer_id'],400);
        }
        if (!$employer = User::find($request->employer_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的用户'],404);
        }
        if ($employer->id !== $work->employer_id) {
            return response()->json(['status' => 0,'msg' => '参数employer_id不是work_id对应兼职的发布者id！'],403);
        }
        if ($review = WorkReviews::where('employer_id',$employer->id)->where('work_id',$work->id)->where('user_id',$user->id)->first()) {
            return response()->json(['status' => 1,'reviewed' => true]);
        } else {
            return response()->json(['status' => 1,'reviewed' => false]);
        }
    }

    public function useful(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        if (!isset($request->review_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数review_id']);
        }
        if (!$review = WorkReviews::find($request->review_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的评价']);
        }
        if ($result = DB::table('work_review_useful')->where('review_id',$review->id)->where('user_id',$user->id)->first()) {
            return response()->json(['status' => 0,'msg' => '你已经点击过有用按钮了']);
        }
        $review->useful()->attach($user->id);
        return response()->json(['status' => 1,'msg' => '操作成功']);
    }
}
