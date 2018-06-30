<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class UsersController extends Controller
{
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

         session()->flash('success','注册成功');

        return redirect()->route('users.show',[$user]);
    }
}
