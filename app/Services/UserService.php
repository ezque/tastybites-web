<?php

namespace App\Services;
use App\Models\User;
use App\Models\UserInfo;

class UserService
{

    public function getChefInfo()
    {
        $userId = auth()->id();

        $chefs = User::with(['userInfo', 'certificates'])
            ->withCount(['followers as followers_count' => function($query) {
                $query->where('status', 'following');
            }])
            ->where('role', 'chef')
            ->inRandomOrder()
            ->get();

        // Add follow status for the logged-in user
        $chefs->map(function($chef) use ($userId) {
            $follow = $chef->followers()->where('followerID', $userId)->first();
            $chef->follow_status = $follow ? $follow->status : 'unfollowed';
            return $chef;
        });

        return $chefs;
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
