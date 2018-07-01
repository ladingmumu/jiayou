<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    //更新操作的授权方法
    public function update(User $currentUser, User $user){
        return $currentUser->id === $user->id;
    }

    //删除用户的授权方法
    public function destroy(User $currentUser, User $user){
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
