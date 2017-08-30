<?php

namespace App\Http\Controllers\Common;

use App\Model\Works;
use App\Model\User;
use App\Model\Tags;
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
        if (isset($request->tag)) {
            $works = Tags::where('name',$request->tag)->first()->works()->with('user','tags');
        } else {
            $works = Works::with('user','tags');}
        if (isset($request->search)) {
            $works = $works->where('title','like','%'.$request->search.'%');
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
        if (isset($request->district)&&$request->district !== "全部") {
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
            $works = $works->orderBy('updated_at','desc');
        }
        if (isset($request->page)) {
            $works = $works->skip(($request->page)*20);
        }
        if (isset($request->id)) {
            $works = $works->where('id',$request->id);
        }
        $works = $works->take(20)->get();
        if (count($works)==0) {
            return response()->json(['status'=>0,'msg'=>'没找到符合条件的兼职','works' => []]);
        } else {
            return response()->json(['status'=>1,'works'=>$works]);
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
        $newWork = Works::create($work);
        $tag_id = explode(',',$work['tags']);
        $skill_id = explode(',',$work['skills']);
        $newWork->tags()->attach($tag_id);
        $newWork->skills()->attach($skill_id);
        return response()->json(['status'=>1,'msg'=>'创建新的兼职成功，请等待审核']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Works  $works
     * @return \Illuminate\Http\Response
     */
    public function show(Works $work)
    {
        if (count($work) == 0) {
            return response()->json(['status'=>0,'msg'=>'找不到对应的兼职']);
        } else {
            $tags = $work->tags()->get();
            $skills = $work->skills()->get();
            $employer = $work->user()->first();
            return response()->json(['status'=>1,'work'=>$work,'tags'=> $tags,'skills'=>$skills,'employer' => $employer]);
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
