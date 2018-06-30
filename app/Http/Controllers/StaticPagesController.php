<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
    //显示首页
    public function home(){
        return view('static_pages.home');
    }

    //显示帮助页
    public function help(){
        return view('static_pages.help');
    }

    //显示关于页
    public function about(){
        return view('static_pages.about');
    }
}
