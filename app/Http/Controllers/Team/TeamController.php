<?php

namespace App\Http\Controllers\Team;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Model\Team;
use Illuminate\Support\Facades\DB;
use App\Model\User;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class TeamController extends Controller
{
    public function __construct() {
        $this->middleware('jwt.auth')->only(['store','update','delete']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->user_id)) {
            if (!$user = User::find($request->user_id)) {
                return response()->json(['status' => 0 ,'msg' => '找不到该用户']);
            } else {
                $team = $user->team()->with(['members','unCheckApplicants'])->first();
                if (isset($team)) {
                    if (isset($team->picture_path))
                    $team->picture_path = explode(',',$team->picture_path);
                }
                return response()->json(['status' => 1, 'team' => $team]);
            }
        }
        if (isset($request->search)) {
            $teams = Team::where('name','like','%'.$request->search.'%');
            if (isset($teams)) {
                foreach ($teams as $team) {
                    if (isset($team->picture_path))
                    $team->picture_path = explode(',',$team->picture_path);
                }
            }
            return response()->json(['status' => 1, 'team' => $teams]);
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
        $team = Team::create($request->all());
        if (!$team) {
            return response()->json(['status' => 0,'msg' => '创建新的团队的时候出错']);
        }
        DB::table('team_member')->insert(['team_id' => $team->id,'user_id' => $owner->id,'title' => '队长','created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
        $team = Team::with(['members','unCheckApplicants'])->where('id',$team->id)->first();
        return response()->json(['status' => 1,'msg' => '创建成功','team' => $team]);
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
        $owner = JWTAuth::parseToken()->authenticate();
        if (!$team = Team::find($id)) {
            return response()->json(['status' => 0,'msg' => '找不到该团队']);
        }
        if ($owner->id !== $team->owner()->first()->id) {
            return response()->json(['status' => 0,'msg'=>'你无权更改该团队的信息']);
        } else {
            if (isset($request->key) && isset($request->value)) {
                $key = $request->key;
                $team->$key = $request->value;
                $result = $team->save();
                return response()->json(['status' => 1,'team'=> $team]);
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
        if (!isset($request->team_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数team_id'],400);
        }
        if (!$team = Team::find($request->team_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的团队'],404);
        }
        if (!$result = DB::table('team_member')->where('user_id',$owner->id)->where('team_id',$team->id)->where('title','队长')->first()) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作！'],400);
        }
        if (!isset($request->picture)) {
            return response()->json(['status' => 0,'msg' => '缺少参数picture'],400);
        }
        $oldPicPath = $team->picture_path;
        $picture = $request->picture;
        $picPath = $picture->store('images');
        if (!$oldPicPath) {
            $newPicPath = $picPath;
        } else {
            $newPicPath = $oldPicPath.','.$picPath;
        }
        $team->picture_path = $newPicPath;
        $team->save();
        return response()->json(['status' =>1,'msg' => '上传成功','picture_path' => $picPath]);
    }

    public function deletePicture(Request $request,$team_id) {
        $owner = JWTAuth::parseToken()->authenticate();
        if (!$team = Team::find($team_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的团队'],404);
        }
        if (!$result = DB::table('team_member')->where('user_id',$owner->id)->where('team_id',$team->id)->where('title','队长')->first()) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作！'],400);
        }
        if (isset($request->delete_path)) {
            $deletePath = $request->delete_path;
        }
        $picture_path = $team->picture_path;
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
        $team->picture_path = $new_path;
        $team->save();
        return response()->json(['status' =>1,'msg' => '删除成功']);
    }

    public function updateLogo(Request $request) {
        $owner = JWTAuth::parseToken()->authenticate();
        if (!isset($request->team_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数team_id'],400);
        }
        if (!$team = Team::find($request->team_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的团队'],404);
        }
        if (!$result = DB::table('team_member')->where('user_id',$owner->id)->where('team_id',$team->id)->where('title','队长')->first()) {
            return response()->json(['status' => 0,'msg' => '你无权进行该操作！'],400);
        }
        $old_logo = $team->logo_path;
        if (isset($request->logo)) {
            $logo = $request->logo;
            $path = $logo->store('images');
            $team->logo_path = $path;
            if ( $team->save()) {
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
