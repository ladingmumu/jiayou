<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;


class UsersController extends Controller
{
    //过滤登录操作
    public function __construct(){

        $this->middleware('auth', [

            'except' => ['show','create','store','index']
        ]);

        $this->middleware('guest', [
            'only' => ['create']
        ]);
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
         Auth::login($user);

         session()->flash('success','注册成功');

        return redirect()->route('users.show',[$user]);
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








