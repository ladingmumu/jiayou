<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPasswrod;
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    //用户模型类完成初始化之后进行加载
    public static function boot(){
        parent::boot();
        static::creating(function ($user){
            $user->activation_token = str_random(30);
        });
    }


    //生成用户的头像
    public function gravatar($size = '100'){
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //发送重置密码通知
    public function sendPasswordResetNotification($token){
        $this->notify(new ResetPassword($token));
    }

    //一个用户拥有多条微博
    public function statuses(){
        return $this->hasMany(Status::class);
    }

    //从数据库获取当前用户发布过的所有微博数据
    public function feed(){
        $user_ids = Auth::user()->followings->pluck('id')->toArray();
        array_push($user_ids, Auth::user()->id);
        return Status::whereIn('user_id', $user_ids)
                            ->with('user')
                            ->orderBy('created_at','desc');

    }

    //获取粉丝列表
    public function followers(){
        return $this->belongsToMany(User::Class,'followers','user_id','follower_id');
    }

    //获取关注人列表
    public function followings(){
        return $this->belongsToMany(User::Class,'followers','follower_id','user_id');
    }

    //关注
    public function follow($user_ids){
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids,false);
    }

    //取消关注
    public function unfollow($user_ids){
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    //判断当前登录的用户A 是否关注了用户B
    public function isFollowing($user_id){
        return $this->followings->contains($user_id);
    }


}
