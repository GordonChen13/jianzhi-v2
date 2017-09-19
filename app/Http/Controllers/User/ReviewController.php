<?php

namespace App\Http\Controllers\User;

use App\Model\Works;
use App\Model\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Model\WorkerReviews;
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
        if (!isset($request->user_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数user_id']);
        }
        if (! $user = User::find($request->user_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的用户，请确认user_id是否正确']);
        }
        if (isset($request->total_star)) {
            $reviews = WorkerReviews::where('user_id',$request->user_id)->get();
            if (count($reviews) == 0) {
                return response()->json(['status' => 0,'msg' => '该用户还没有兼职评价']);
            }
            $attitude_star = number_format($reviews->avg('attitude_star'),1);
            $ability_star = number_format($reviews->avg('ability_star'),1);
            $description_match = number_format($reviews->avg('description_match'),1);
            $total_star =  number_format(($attitude_star + $ability_star + $description_match) / 3,1);
            return response()->json(['status' => 1,'total_star' => $total_star,'attitude_star' => $attitude_star,
                'ability_star' => $ability_star,'description_match' => $description_match]);
        }
        $reviews = WorkerReviews::with('keywords')->withCount(['useful','reply'])->where('user_id',$request->user_id);
        $reviews = $reviews->orderBy('created','desc')->get();
        foreach ($reviews as $review) {
            if (isset($review->pic_path)) {
                $review->pic_path = explode(',',$review->pic_path);
            } else {
            $review->pic_path = [];
            }
            $review->pic_path = explode(',',$review->pic_path);
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
        $employer = JWTAuth::parseToken()->authenticate();
        if (!isset($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数work_id'],400);
        }
        if (!$work = Works::find($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的兼职'],404);
        }
        if ($employer->id !== $work->employer_id) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作！'],403);
        }
        if (!isset($request->user_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数user_id'],400);
        }
        if (!$user = User::find($request->user_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的用户'],404);
        }
        if (!isset($request->attitude_star)) {
            return response()->json(['status' => 0,'msg' => '缺少参数attitude_star'],400);
        }
        if (!isset($request->ability_star)) {
            return response()->json(['status' => 0,'msg' => '缺少参数ability_star'],400);
        }
        if (!isset($request->description_match)) {
            return response()->json(['status' => 0,'msg' => '缺少参数description_match'],400);
        }
        if (!isset($request->text)) {
            return response()->json(['status' => 0,'msg' => '缺少参数text']);
        }
        if ($review = WorkerReviews::where('user_id',$user->id)->where('work_id',$work->id)->first()) {
            return response()->json(['status' => 0,'msg' => '你已经评价过了，不能重复评价']);
        }
        $review = new WorkerReviews();
        $review->user_id = $user->id;
        $review->work_id = $work->id;
        $review->attitude_star = $request->attitude_star;
        $review->ability_star = $request->ability_star;
        $review->description_match = $request->description_match;
        $review->content = $request->text;
        $review->save();
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
        $employer = JWTAuth::parseToken()->authenticate();
        if (!isset($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数work_id'],400);
        }
        if (!$work = Works::find($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的兼职'],404);
        }
        if ($employer->id !== $work->employer_id) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作！'],403);
        }
        if (!isset($request->user_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数user_id'],400);
        }
        if (!$user = User::find($request->user_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的用户'],404);
        }
        if (!$review = WorkerReviews::where('user_id',$user->id)->where('work_id',$work->id)->first()) {
            return response()->json(['status' => 0,'msg' => '找不到对应的评价'],404);
        }
        $oldPicPath = $review->pic_path;
        $picture = $request->picture;
        $picPath = $picture->store('images');
        if (!$oldPicPath) {
            $newPicPath = $picPath;
        } else {
            $newPicPath = $oldPicPath.','.$picPath;
        }
        $review->pic_path = $newPicPath;
        $review->save();
        return response()->json(['status' =>1,'msg' => '上传成功','work_id' => $request->work_id,'user_id' => $request->user_id]);
    }

    public function checkReviewed(Request $request) {
        $employer = JWTAuth::parseToken()->authenticate();
        if (!isset($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数work_id'],400);
        }
        if (!$work = Works::find($request->work_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的兼职'],404);
        }
        if ($employer->id !== $work->employer_id) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作！'],403);
        }
        if (!isset($request->user_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数user_id'],400);
        }
        if (!$user = User::find($request->user_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的用户'],404);
        }
        if ($review = WorkerReviews::where('user_id',$user->id)->where('work_id',$work->id)->first()) {
            return response()->json(['status' => 1,'reviewed' => true]);
        } else {
            return response()->json(['status' => 1,'reviewed' => false]);
        }
    }
}
