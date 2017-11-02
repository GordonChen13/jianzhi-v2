<?php

namespace App\Http\Controllers\Company;

use App\Model\Employer;
use Illuminate\Http\Request;
use App\Model\Company;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Model\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        if (!isset($request->company_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数company_id']);
        }
        if (!isset($request->employer_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数employer_id']);
        }
        if (!$company = Company::find($request->company_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的公司']);
        }
        if ($user->id !== $company->owner()->first()->id) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作！']);
        }
        if (!$employer = Employer::find($request->employer_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的用户']);
        }
        if ($result = DB::table('employer_company')->where('employer_id',$employer->id)->first()) {
            return response()->json(['status' => 0,'msg' => '该用户已加入别的公司了，不能同时属于两个公司']);
        }
        DB::table('employer_company')->insert(['employer_id' => $employer->id,'company_id' => $company->id,'title' => '成员','created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
        return response()->json(['status' => 1,'msg' => '操作成功']);
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
        if (!isset($request->title)) {
            return response()->json(['status' => 0,'msg' => '缺少参数title']);
        }
        if (!isset($request->company_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数company_id']);
        }
        if (!$company = Company::find($request->company_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的公司']);
        }
        $owner = JWTAuth::parseToken()->authenticate();
        $companyOwner = $company->owner()->first();
        if ($owner->id !== $companyOwner->id) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作']);
        }
        if (!$result = DB::table('employer_company')->where('company_id',$company->id)->where('employer_id',$id)->first()) {
            return response()->json(['status' => 0,'msg' => '在队员里面找不到该用户，请确认id是否正确']);
        }
        DB::table('employer_company')->where('company_id',$company->id)->where('employer_id',$id)->update(['title' => $request->title]);
        return response()->json(['status' => 1,'msg' => '更改完成']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        if (!isset($request->company_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数company_id']);
        }
        if (!$company = Company::find($request->company_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的公司']);
        }
        if (isset($request->role) && $request->role == 'owner') {
            $owner = JWTAuth::parseToken()->authenticate();
            $companyOwner = $company->owner()->first();
            if ($owner->id != $companyOwner->id) {
                return response()->json(['status' => 0,'msg' => '你无权进行该操作']);
            }
        } else {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user->id != $id) {
                dd($user,$id);
                return response()->json(['status' => 0,'msg' => '你无权进行该操作']);
            }
        }
        if (!$result = DB::table('employer_company')->where('company_id',$company->id)->where('employer_id',$id)->first()) {
            return response()->json(['status' => 0,'msg' => '在队员里面找不到该用户，请确认id是否正确']);
        }
        DB::table('employer_company')->where('company_id',$company->id)->where('employer_id',$id)->delete();
        return response()->json(['status' => 1,'msg' => '操作成功']);
    }

}
