<?php

namespace App\Http\Controllers\Common;

use App\Events\Employer\WorkCreated;
use App\Events\Work\WorkViewed;
use App\Model\WorkReviews;
use App\Model\Works;
use App\Model\Employer;
use App\Model\Tags;
use App\Services\WorkService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class WorkController extends Controller
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
        $works = Works::with('employer','tags')->withCount(['questions','favoriteUser','applicants']);
        if (isset($request->recommend) && !empty($request->recommend)) {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['status' => 0,'msg' => '你还没登录，请先登录']);
            }
            $workService = new WorkService();
            $reccommendWorks = $workService->getRecommendWorks($user);
            return response()->json(['status' => 1,'works' => $reccommendWorks]);
        }
        if (isset($request->hot) && !empty($request->hot)) {
            $workService = new WorkService();
            $hotWorks = $workService->getHotWorks($request->order);
            return response()->json(['status' => 1,'works' => $hotWorks]);
        }
        if (isset($request->similar) && !empty($request->similar)) {
            if (!isset($request->id)) {
                return response()->json(['status' => 0,'msg' => '缺少参数id']);
            }
            if (!$work = Works::find($request->id)) {
                return response()->json(['status' => 0,'msg' => '找不到该兼职，请确认id是否正确']);
            }
            $workService = new WorkService();
            $similarWorks = $workService->getSimilarWorks($request->id);
            return response()->json(['status' => 1,'works' => $similarWorks]);
        }
        if (isset($request->search)) {
            $works = $works->where('title','like','%'.$request->search.'%');
        }
        if (isset($request->tag) && !empty($request->tag)) {
            $tag = $request->tag;
            $works = $works->whereHas('tags',function ($query) use($tag) {
                return $query->where('name',$tag);
            });
        }
        if (isset($request->status)) {
            if ($request->status == '已结束') {
                $works = $works->where('status', '>', 1);
            } else if ($request->status == '非审核') {
                $works = $works->where('status', '>', 0);
            } else {
                $works = $works->where('status', $request->status);
            }
        }
        if (isset($request->city)) {
            $works = $works->where('city',$request->city);
        }
        if (isset($request->employer_id)) {
            $works = $works->where('employer_id',$request->employer_id);
        }
        if (isset($request->district) && $request->district !== "全部") {
            $works = $works->where('district',$request->district);
        }
        if (isset($request->pay_time)) {
            $works = $works->where('pay_time',$request->pay_time);
        }
        if (isset($request->pay_type)) {
            $works = $works->where('pay_type',$request->pay_type);
        }
        if (isset($request->settlement_type)) {
            $works = $works->where('settlement_type',$request->settlement_type);
        }
        if (isset($request->gender)) {
            $works = $works->where('gender',$request->gender);
        }
        if (isset($request->has_commission)) {
            $request->has_commission = $request->has_commission == 'true' ? 1 : 0;
            $works = $works->where('has_commission',$request->has_commission);
        }
        if (isset($request->need_interview)) {
            $request->need_interview = $request->need_interview == 'true' ? 1 : 0;
            $works = $works->where('need_interview',$request->need_interview);
        }
        if (isset($request->orderBy)) {
            $order = isset($request->order) ? $request->order : 'asc';
            $works = $works->orderBy($request->orderBy,$order);
        } else {
            $works = $works->orderBy('created_at','desc');
        }
        if (isset($request->id)) {
            $works = $works->where('id',$request->id);
        }
        $works = $works->paginate(10);
        foreach ($works->items() as $work) {
            if (strpos($work->lunch,',')) {
                $work->lunch = explode(',',$work->lunch);
            } else {
                $work->lunch = array($work->lunch);
            }
        }
        return response()->json(['status'=>1,'works'=>$works]);
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
        $messages = [
            'required' => ':attribute不能为空',
            'numeric' => ':attribute只能是数字',
            'JSON' => ':attribute只能是JSON格式',
            'boolean' => ':attribute只能为boolean'
        ];
        $validator = Validator::make($request->all(),[
            'work' => 'required',
            'work.title' => 'required',
            'work.start_date' => 'required',
            'work.end_date' => 'required',
            'work.start_time' => 'required',
            'work.end_time' => 'required',
            'work.city' => 'required',
            'work.district' => 'required',
            'work.address' => 'required',
            'work.need_num' => 'required|numeric',
            'work.pay_amount' => 'required',
            'work.settlement_type' => 'required',
            'work.pay_type' => 'required',
            'work.has_commission' => 'required',
            'work.gender' => 'required',
            'work.contact' => 'required',
            'work.need_interview' => 'required|boolean',
            'work.requirements' => 'required',
            'work.description' => 'required',
            'work.employer_id' => 'required',
            'work.tags' => 'required'
        ],$messages);
        if($validator->fails()){
            $errors = $validator->errors()->first();
            return response()->json(['status'=>0,'msg'=>$errors,'error'=>$errors ]);
        }
        $work = $request->work;
        $work['has_commission'] = (int)$work['has_commission'];
        $work['need_interview'] = (int)$work['need_interview'];
        //TODO 添加兼职审核机制,并把status去掉
        $work['status'] = 1;
        $newWork = Works::create($work);
        if (count($work['tags']) > 0) {
            $tag_id = explode(',',$work['tags']);
            $newWork->tags()->attach($tag_id);
        }
        if (count($work['skills']) > 0) {
            $skill_id = explode(',',$work['skills']);
            $newWork->skills()->attach($skill_id);
        }
        $employer = Employer::find($user->id);
        event(new WorkCreated($newWork,$employer));
        return response()->json(['status'=>1,'msg'=>'创建新的兼职成功，请等待审核']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Works  $works
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $work = Works::with('tags','skills')->withCount(['questions','favoriteUser','applicants'])->find($id);
        if (count($work) == 0) {
            return response()->json(['status'=>0,'msg'=>'找不到对应的兼职']);
        } else {
            $ip = $request->ip();
            event(new WorkViewed($work,$ip));
            $employer = Employer::withCount('checkedWorks','reviews','userFollowers','applyingWorks')->find($work->employer_id);
            $reviews = WorkReviews::where('employer_id',$employer->id)->get();
            $treat_star = number_format($reviews->avg('treat_star'),1);
            $pay_speed = number_format($reviews->avg('pay_speed'),1);
            $description_match = number_format($reviews->avg('description_match'),1);
            $total_star =  number_format(($treat_star + $pay_speed + $description_match) / 3,1);
            $employer->treat_star = (float)$treat_star;
            $employer->pay_speed = (float)$pay_speed;
            $employer->description_match = (float)$description_match;
            $employer->total_star = (float)$total_star;
            $work->employer = $employer;
            if (strpos($work->lunch,',')) {
                $work->lunch = explode(',',$work->lunch);
            } else {
                $work->lunch = array($work->lunch);
            }
            return response()->json(['status'=>1,'work'=>$work]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Works  $works
     * @return \Illuminate\Http\Response
     */
    public function edit(Works $works)
    {
        dd("edit");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Works  $works
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Works $works)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Works  $works
     * @return \Illuminate\Http\Response
     */
    public function destroy(Works $works)
    {
        //
    }

    public function getEmployerWorks(Request $request,$id)
    {
        $works = Works::where('employer_id',$id)->where('status',$request->status)->get();
        return response()->json(['status'=>1,'works'=>$works]);
//        var_dump([$id,$request->status]);
    }
}
