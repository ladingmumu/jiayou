<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Mail;

class UsersController extends Controller
{
    //过滤登录操作
    public function __construct(){

        $this->middleware('auth', [

            'except' => ['show','create','store','index','confirmEmail']
        ]);

        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    //用户的激活
    public function confirmEmail($token){
        $user = User::where('activation_token', $token)->firstOrFail();
        $user->activation_token =null;
        $user->activated = true;
        $user->save();

        Auth::login($user);
        session()->flash('success','恭喜你，激活成功');
        return redirect()->route('users.show',[$user]);
    }

    //显示注册页
    public function create(){
        return view('users.create');
    }

    //显示用户个人信息的页面
    public function show(User $user){
        return view('users.show',compact('user'));
    }

    //创建用户
    public function store(Request $request){
        //输入验证
         $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        //用户权限验证
         $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),

         ]);

         $this->sendEmailConfirmationTo($user);
         session()->flash('success','验证邮件已发送到您注册邮箱上，请注意查收');

        return redirect('/');
    }

    //发送邮件给指定用户
    protected function sendEmailConfirmationTo($user){
        $view = 'emails.confirm';
        $data = compact('user');
        $from = '295741509@qq.com';
        $name = 'qianqian';
        $to   = $user->email;
        $subject = '感谢注册 JIA YOU 应用！请确认您的邮箱';

        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject){
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    //显示编辑表单
    public function edit(User $user){
        $this->authorize('update', $user);
        return view('users.edit',compact('user'));
    }

    //更新个人资料
    public function update(User $user, Request $request){
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6',
        ]);

        $this->authorize('update', $user);
        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);
        session()->flash('success','个人信息更新成功');
        return redirect()->route('users.show',$user->id);
    }

    //显示所有用户
    public function index(){

        $users = User::paginate(5);

        return view('users.index',compact('users'));
    }

    //删除用户
    public function destroy(User $user){
        $this->authorize('destroy',$user);
        $user->delete();
        session()->flash('success','成功删除用户');
        return redirect()->back();
    }
}








