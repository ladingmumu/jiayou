@extends('layouts.default')
@section('title','home')
@section('content')
    @if (Auth::check())
        <div class="row">
            <div class="col-md-8">
                <section class="status_form">
                    @include('shared._status_form')
                </section>
                <h3>微博列表</h3>
                @include('shared._feed')
            </div>
            <aside class="col-md-4">
                <section class="user_info">
                    @include('shared._user_info',['user'=>Auth::user()])
                </section>
            </aside>
        </div>
    @else
        <div class="jumbotron">
            <h1>欢迎 JIA YOU</h1>
            <p class="lead">
                魔法王国欢迎您
            </p>
            <p>
                一切，从这里开始
            </p>
            <button class="btn btn-lg btn-success">注册</button>
        </div>
    @endif
@stop