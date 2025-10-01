<?php

namespace App\Services;
use App\Models\User;
use App\Models\UserInfo;

class UserService
{

    public function getChefInfo()
    {
        return User::with(['userInfo', 'certificates'])
            ->where('role', 'chef')
            ->get();
    }
    public function totalCountsUsers()
    {
        return [
            'usersCounts' => User::where('role', 'user')->count(),
            'chefsCounts' => User::where('role', 'chef')->count(),
        ];
    }


    public function getUserInfo()
    {
        return User::with('userInfo')
            ->where('role', 'user')
            ->get();
    }
}
