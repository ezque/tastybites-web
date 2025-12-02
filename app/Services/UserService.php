<?php

namespace App\Services;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Purchase;
class UserService
{

    public function getChefInfo()
    {
        $userId = auth()->id();

        $chefs = User::with(['userInfo', 'certificates'])
            ->withCount(['followers as followers_count' => function($query) {
                $query->where('status', 'true');
            }])
            ->where('role', 'chef')
            ->inRandomOrder()
            ->get();

        // Add follow status for the logged-in user
        $chefs->map(function($chef) use ($userId) {
            $follow = $chef->followers()->where('followerID', $userId)->first();
            $chef->follow_status = $follow ? $follow->status : 'false';
            return $chef;
        });

        return $chefs;
    }
    public function getTopChefs()
    {
        $chefs = User::with(['userInfo', 'certificates'])
            ->withCount(['ownedRecipes as total_purchases_count' => function($query) {
                $query->join('purchases', 'recipes.id', '=', 'purchases.recipeID')
                    ->where('purchases.status', 'confirmed'); // Only count confirmed purchases
            }])
            ->withCount(['followers as followers_count' => function($query) {
                $query->where('status', 'true');
            }])
            ->where('role', 'chef')
            ->orderByDesc('total_purchases_count')
            ->take(6)
            ->get();

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
    public function chefLeaderboards()
    {
        return User::where('role', 'chef')
            ->where('status', 'active') // this is users.status
            ->with('userInfo')
            ->withSum(['recipePurchases as total_revenue' => function ($query) {
                $query->where('purchases.status', 'confirmed'); // <-- specify table
            }], 'amount')
            ->orderByDesc('total_revenue')
            ->take(20)
            ->get();
    }




}
