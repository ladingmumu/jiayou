<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    public function __construct(){
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    //显示登录页面
    public function create(){
        return view('sessions.create');
    }

    //用户登录验证
    public function store( Request $request){
        $credentials =$this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            //认证通过
            if (Auth::user()->activated) {
                session()->flash('success','登录成功');
                return redirect()->intended(route('users.show',[Auth::user()]));
            }else{
                Auth::logout();
                session()->flash('warning','您的账户未激活，请检查邮箱中的注册邮件进行激活');
                return redirect('/');
            }

        }else{
            //认证失败
            session()->flash('danger','很抱歉，邮箱和密码不匹配');
            return redirect()->back();
        }
        return;
    }

    //退出登录
    public function destroy(){
        Auth::logout();
        session()->flash('success','您已经成功退出');
        return redirect()->route('login');
    }
}
