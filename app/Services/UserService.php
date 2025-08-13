<?php

namespace App\Services;
use App\Models\User;
use App\Models\UserInfo;

class UserService
{

    public function getChefInfo()
    {
        return User::with('userInfo')
            ->where('role', 'chef')
            ->get();
    }
    public function getUserInfo()
    {
        return User::with('userInfo')
            ->where('role', 'user')
            ->get();
    }
}
