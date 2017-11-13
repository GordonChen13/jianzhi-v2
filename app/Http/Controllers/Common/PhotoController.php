<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Model\User;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class PhotoController extends Controller
{
    public function __construct() {
        $this->middleware('jwt.auth')->only(['store','update','updateUserAvatar','updateUserCover']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json(['auth_token' => $request->header('Authorization'),'user' => $user]);
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
        $user = User::find(1);
        if (isset($request->avatar)) {
            $avatar = $request->avatar;
            $path = $avatar->store('images');
                $user->pic_path = $path;
                $user = $user->save();
            return response()->json(['status' => 1,'pic_path'=> $path,'user'=>$user]);
        } else {
            return response()->json(['status' => 0,'msg' => '上传的照片不能为空','photo' => $request->avatar,'name'=> $request->name]);
        }
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
    public function update(Request $request)
    {
        $user = User::find(1);

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

    public function updateUserAvatar(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        $old_pic = $user->pic_path;
        if (isset($request->avatar)) {
            $avatar = $request->avatar;
            $path = $avatar->store('images');
            $user->pic_path = $path;
            if ( $user->save()) {
                if ($old_pic != '/images/default.png')  Storage::delete($old_pic);
                return response()->json(['status' => 1,'pic_path'=> $path]);
            } else {
                return response()->json(['status' => 0,'msg' => '照片保存失败，请稍后再试'],500);
            }
        } else {
            return response()->json(['status' => 0,'msg' => '上传的照片不能为空','photo' => $request->avatar,'name'=> $request->name],400);
        }
    }

    public function updateUserCover(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        $old_pic = $user->cover_path;
        if (isset($request->cover)) {
            $cover = $request->cover;
            $path = $cover->store('images');
            $user->cover_path = $path;
            if ( $user->save()) {
                Storage::delete($old_pic);
                return response()->json(['status' => 1,'pic_path'=> $path]);
            } else {
                return response()->json(['status' => 0,'msg' => '照片保存失败，请稍后再试'],500);
            }
        } else {
            return response()->json(['status' => 0,'msg' => '上传的照片不能为空'],400);
        }
    }
}
