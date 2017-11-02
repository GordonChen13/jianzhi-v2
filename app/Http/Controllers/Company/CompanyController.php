<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Company;
use App\Model\Employer;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->employer_id)) {
            if (!$employer = Employer::find($request->employer_id)) {
                return response()->json(['status' => 0 ,'msg' => '找不到该雇主']);
            } else {
                $company = $employer->company()->with('members')->first();
                if (isset($company)) {
                    if ($company->picture_path != '') {
                        $company->picture_path = explode(',',$company->picture_path);
                    } else {
                        $company->picture_path = array();
                    }
                }
                return response()->json(['status' => 1, 'company' => $company]);
            }
        }
        if (isset($request->search)) {
            $companys = Company::where('name','like','%'.$request->search.'%');
            if (isset($companys)) {
                foreach ($companys as $company) {
                    if ($company->picture_path !== '')
                    $company->picture_path = explode(',',$company->picture_path);
                }
            }
            return response()->json(['status' => 1, 'company' => $companys]);
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
        $owner = JWTAuth::parseToken()->authenticate();
        if (!isset($request->name)) {
            return response()->json(['status' => 0,'msg' => '缺少参数name']);
        }
        $company = Company::create($request->all());
        if (!$company) {
            return response()->json(['status' => 0,'msg' => '创建新的公司的时候出错']);
        }
        DB::table('employer_company')->insert(['company_id' => $company->id,'employer_id' => $owner->id,'title' => 'owner','created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
        $company = Company::with('members')->where('id',$company->id)->first();
        if ($company->picture_path != '') {
            $company->picture_path = explode(',',$company->picture_path);
        } else {
            $company->picture_path = array();
        }

        return response()->json(['status' => 1,'msg' => '创建成功','company' => $company]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!$company = Company::find($id)) {
            return response()->json(['status' => 0,'msg' => '找不到该公司，请确认id是否正确']);
        }
        return response()->json(['status' => 1,'company' => $company]);
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
        $owner = JWTAuth::parseToken()->authenticate();
        if (!$company = Company::find($id)) {
            return response()->json(['status' => 0,'msg' => '找不到该公司']);
        }
        if ($owner->id !== $company->owner()->first()->id) {
            return response()->json(['status' => 0,'msg'=>'你无权更改该公司的信息']);
        } else {
            if (isset($request->key) && isset($request->value)) {
                $key = $request->key;
                $company->$key = $request->value;
                $result = $company->save();
                return response()->json(['status' => 1,'company'=> $company]);
            } else {
                return response()->json(['status' => 0 ,'msg' => '参数不对']);
            }
        }
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

    public function storePicture(Request $request) {
        $owner = JWTAuth::parseToken()->authenticate();
        if (!isset($request->company_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数company_id'],400);
        }
        if (!$company = Company::find($request->company_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的公司'],404);
        }
        if (!$result = DB::table('employer_company')->where('employer_id',$owner->id)->where('company_id',$company->id)->where('title','owner')->first()) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作！'],400);
        }
        if (!isset($request->picture)) {
            return response()->json(['status' => 0,'msg' => '缺少参数picture'],400);
        }
        $oldPicPath = $company->picture_path;
        $picture = $request->picture;
        $picPath = $picture->store('images');
        if (!$oldPicPath) {
            $newPicPath = $picPath;
        } else {
            $newPicPath = $oldPicPath.','.$picPath;
        }
        $company->picture_path = $newPicPath;
        $company->save();
        return response()->json(['status' =>1,'msg' => '上传成功','picture_path' => $picPath]);
    }

    public function deletePicture(Request $request,$company_id) {
        $owner = JWTAuth::parseToken()->authenticate();
        if (!$company = Company::find($company_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的公司'],404);
        }
        if (!$result = DB::table('employer_company')->where('employer_id',$owner->id)->where('company_id',$company->id)->where('title','owner')->first()) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作！'],400);
        }
        if (isset($request->delete_path)) {
            $deletePath = $request->delete_path;
        }
        $picture_path = $company->picture_path;
        $new_path = '';
        $pictures = explode(',',$picture_path);
        foreach ($pictures as $picture) {
            if ($new_path !=='' ) {
                if ($picture == $deletePath) {
                    Storage::delete($picture);
                } else {
                    $new_path = $new_path.','.$picture;
                }
            } else {
                if ($picture == $deletePath) {
                    Storage::delete($picture);
                } else {
                    $new_path = $picture;
                }
            }
        }
        $company->picture_path = $new_path;
        $company->save();
        return response()->json(['status' =>1,'msg' => '删除成功']);
    }

    public function storeLicence(Request $request) {
        $owner = JWTAuth::parseToken()->authenticate();
        if (!isset($request->company_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数company_id'],400);
        }
        if (!$company = Company::find($request->company_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的公司'],404);
        }
        if (!$result = DB::table('employer_company')->where('employer_id',$owner->id)->where('company_id',$company->id)->where('title','owner')->first()) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作！'],400);
        }
        if (!isset($request->licence)) {
            return response()->json(['status' => 0,'msg' => '缺少参数licence'],400);
        }
        $old_path = $company->licence_path;
        $picture = $request->licence;
        $licencePath = $picture->store('images');
        $company->licence_path = $licencePath;
        $company->save();
        Storage::delete($old_path);
        return response()->json(['status' =>1,'msg' => '上传成功','licence_path' => $licencePath]);
    }

    public function updateLogo(Request $request) {
        $owner = JWTAuth::parseToken()->authenticate();
        if (!isset($request->company_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数company_id'],400);
        }
        if (!$company = Company::find($request->company_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的公司'],404);
        }
        if (!$result = DB::table('employer_company')->where('employer_id',$owner->id)->where('company_id',$company->id)->where('title','owner')->first()) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作！'],400);
        }
        $old_logo = $company->logo_path;
        if (isset($request->logo)) {
            $logo = $request->logo;
            $path = $logo->store('images');
            $company->logo_path = $path;
            if ( $company->save()) {
                if($old_logo !== 'images/defaultlogo.png')  Storage::delete($old_logo);
                return response()->json(['status' => 1,'msg'=> '操作成功','logo_path' => $path]);
            } else {
                return response()->json(['status' => 0,'msg' => '照片保存失败，请稍后再试'],500);
            }
        } else {
            return response()->json(['status' => 0,'msg' => '上传的照片不能为空','photo' => $request->logo],400);
        }
    }
}
