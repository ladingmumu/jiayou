<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class FollowersController extends Controller
{
    //过滤需要用户登录后才能进行的操作
    public function __construct(){
        $this->middleware('auth');
    }

    //关注
    public function store(User $user){
        if (Auth::user()->id === $user->id) {
            return redirect()->back();
        }
        if (!Auth::user()->isFollowing($user->id)) {
            Auth::user()->follow($user->id);
        }
        return redirect()->route('users.show',$user->id);
    }

    //取消关注
    public function destroy(User $user){
        if (Auth::user()->id === $user->id) {
            return redirect()->back();
        }
        if (Auth::user()->isFollowing($user->id)) {
            Auth::user()->unfollow($user->id);
        }
        return redirect()->route('users.show',$user->id);
    }
}
