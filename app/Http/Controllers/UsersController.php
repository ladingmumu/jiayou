<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    //显示注册页
    public function create(){
        return view('users.create');
    }
}
