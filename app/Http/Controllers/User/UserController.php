<?php

namespace App\Http\Controllers\User;

use App\Model\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class UserController extends Controller
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
        $users = User::withCount(['finishedWorks','userFollowers','reviews']);
        if (isset($request->search)) {
            $users = $users->where('name','like','%'.$request->search.'%');
        }
        $users =$users->get();
        return response()->json(['status' => 1,'users'=>$users]);
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
    public function store(Request $request, $id)
    {
        dd($id);
        /*$user = User::find($id);
        dd($user);
        $Token_user = JWTAuth::parseToken()->authenticate();
        if ($Token_user->id !== $user->id) {
            return response()->json(['status' => 0, 'msg' => '你无权更改该用户的信息', 'user' => $user, 'Token_user' => $Token_user]);
        } else {
            if (isset($request->avatar)) {
                $avatar = $request->avatar;
                $path = $avatar->store('images');
                $user->pic_path = $path;
                $user = $user->save();
                return response()->json(['status' => 1, 'photo' => $path, 'user' => $user]);
            } else {
                return response()->json(['status' => 0, 'msg' => '上传的照片不能为空', 'photo' => $request->avatar, 'name' => $request->name]);
            }
        }*/
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!$user = User::find($id)) {
            return response()->json(['status' => 0,'msg' => '找不到该用户']);
        };
        return response()->json(['status' => 1, 'user'=> $user]);
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
        $Token_user = JWTAuth::parseToken()->authenticate();
        if ($Token_user->id !== $user->id) {
            return response()->json(['status' => 0,'msg'=>'你无权更改该用户的信息','user' => $user,'Token_user' => $Token_user]);
        } else {
            if (isset($request->key) && isset($request->value)) {
                $key = $request->key;
                $user->$key = $request->value;
                $result = $user->save();
                return response()->json(['status' => 1,'user'=> $user]);
            } else {
                return response()->json(['status' => 0 ,'msg' => '参数不对']);
            }
        }

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
