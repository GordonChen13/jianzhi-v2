<?php

namespace App\Http\Controllers\Auth;

//use App\Mail\SendRandomCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use Cache;
use Auth;
use Illuminate\Support\Facades\Redis;
use App\Model\User;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Events\User\Login;
use App\Events\User\Register;
use App\Events\User\Logout;

class AuthenticateController extends Controller
{

    public function _construct(){
        $this->middleware('guest',['except'=>'logout']);
    }

    /**用户注册
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request){
        $messages = [
            'required' => ':attribute不能为空',
            'email' => '请输入正确的邮箱',
            'unique' => '该:attribute已经被注册',
            'confirmed' => '两次输入的密码不一样'
        ];
        $validator = Validator::make($request->all(),[
            'email' => 'required|unique:users|max:255|email',
            'name' => 'required',
            'password' => 'required|confirmed',
        ],$messages);
        if($validator->fails()){
            $errors = $validator->errors()->first();
            return response()->json(['status'=>0,'msg'=>$errors,'user'=>[] ]);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        if(!$user->save())
            return response()->json(['status'=>0,'msg'=>'用户数据写入数据库失败，请重试','user'=>[] ]);
        $token = JWTAuth::fromUser($user);
        event(new Register($user));
        $user =User::withCount(['works','interviewFailedWorks','rejectedWorks','applyingWorks','interviewingWorks','workingWorks',
            'reviewingWorks','finishedWorks','favoriteWorks','userFollowers','reviews'])->find($user->id);
        return response()->json(['status'=>1,'token'=>$token,'user'=>$user]);
    }

    /**用户登录
     * @param Request $request [email,password]
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        $messages = [
            'required' => ':attribute不能为空',
            'email' => '请输入正确的邮箱',
            'min:6'  => '请设置大于6位数的密码',
        ];
        $validator = Validator::make($request->all(),[
            'email' => 'required|max:255|email',
            'password' => 'required|min:6',
        ],$messages);
        if($validator->fails()){
            $errors = $validator->errors()->first();
            return response()->json(['status'=>0,'msg'=>$errors,'user'=>[] ]);
        }
        $credentials = $request->only('email', 'password');
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['status'=>0,'msg' => '验证失败，帐号或者密码不正确','user'=>[] ]);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['status'=>0,'msg' => '无法生成token，请稍后再试','user'=>[] ]);
        }
        $user =User::withCount(['works','interviewFailedWorks','rejectedWorks','applyingWorks','interviewingWorks','workingWorks',
            'reviewingWorks','finishedWorks','favoriteWorks','userFollowers','reviews','unReadMessages'])->where('email',$request->email)->first();
        event(new Login($user));
        return response()->json(['status'=>1,'token'=>$token,'user'=>$user]);
    }

    public function logout() {
        $user = JWTAuth::parseToken()->authenticate();
        event(new Logout($user));
        return response()->json(['status' =>1,'msg' => '你已经退出了登录']);
    }

    public function refreshToken() {

        if (!$user = $this->getUserFromToken())
            return response()->json(['status'=>0,'msg' => 'token出错，请稍后再试','token'=>$user ]);
        $token = JWTAuth::fromUser($user);
        event(new Login($user));
        $user =User::withCount(['works','interviewFailedWorks','rejectedWorks','applyingWorks','interviewingWorks','workingWorks',
            'reviewingWorks','finishedWorks','favoriteWorks','userFollowers','reviews','unReadMessages'])->find($user->id);
        return response()->json(['status'=>1,'token'=>$token,'user'=>$user]);
    }

    /**登录的用户更改密码
     * @param Request $request [email,old_password,new_password]
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request){
        $messages = [
            'required' => ':attribute不能为空',
            'email' => '请输入正确的邮箱',
            'min:6'  => '请设置大于6位数的密码',
        ];
        $validator = Validator::make($request->all(),[
            'email' => 'required|max:255|email',
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6',
        ],$messages);
        if($validator->fails()){
            $errors = $validator->errors()->first();
            return response()->json(['status'=>0,'msg'=>$errors,'user'=>[] ]);
        }
        if (!$user = $this->getUserFromToken())
            return response()->json(['status'=>0,'msg' => '您还没登录，请先登录','user'=>[] ]);
        if(!Auth::attempt(['email'=>$request->email,'password'=>$request->old_password]))
            return response()->json(['status'=>0,'msg' => '密码错误,请输入正确的帐号或者密码','user'=>[] ]);
        $new_password = $request->new_password;
        $user->password = Hash::make($new_password);
        if (!$user->save())
            return response()->json(['status'=>0,'msg' => '保存新密码时出错，请稍后再试','user'=>[] ]);
        return response()->json(['status'=>1,'msg' => '已经生成新的密码']);
    }

    /**给用户发送用于重置忘记的密码的验证码
     * @param Request $request [email]
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendRandomCode(Request $request){
        $messages = [
            'required' => ':attribute不能为空',
            'email' => '请输入正确的邮箱',
        ];
        $validator = Validator::make($request->all(),[
            'email' => 'required|max:255|email',
        ],$messages);
        if($validator->fails()){
            $errors = $validator->errors()->first();
            return response()->json(['status'=>0,'msg'=>$errors],401);
        }
        $email = $request->email;
        if (!$user = User::where('email',$email)->first())
            return response()->json(['status'=>0,'msg' => '该邮箱还没有注册，请先注册'], 401);
        $code = rand(10000,100000);
        Cache::put('user:'.$user->id.'randomcode',$code,120);
//        Mail::to($user)->send(new SendRandomCode($code));
        return response()->json(['status'=>1,'msg' => '验证码已经发送，请登录邮箱查收']);
    }

    /**给忘记密码的用户生成新的密码
     * @param Request $request [email,code,password]
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetForgottenPassword(Request $request){
        $messages = [
            'required' => ':attribute不能为空',
            'email' => '请输入正确的邮箱',
            'min:6'  => '请设置大于6位数的密码',
        ];
        $validator = Validator::make($request->all(),[
            'email' => 'required|max:255|email',
            'password' => 'required|min:6',
        ],$messages);
        if($validator->fails()){
            $errors = $validator->errors()->first();
            return response()->json(['status'=>0,'msg'=>$errors],401);
        }
        $email = $request->email;
        if (!$user = User::where('email',$email)->first())
            return response()->json(['status'=>0,'msg' => '该邮箱还没有注册，请先注册'], 401);
        $code = Cache::get('user:'.$user->id.'randomcode');
        if ($request->code !== $code)
            return response()->json(['status'=>0,'msg' => '验证码不正确'], 401);
        $user->password = Hash::make($request->password);
        if (!$user->save())
            return response()->json(['status'=>0,'msg' => '保存新密码时出错，请稍后再试'], 500);
        return response()->json(['status'=>1,'msg' => '已经生成新的密码']);

    }
    /**根据token返回用户信息
     * @return mixed
     */
    public function getUserFromToken(){
        try {
            $user = JWTAuth::parseToken()->authenticate();
        }catch (JWTException $e) {
            return false;
        }
        return $user;
    }
}
