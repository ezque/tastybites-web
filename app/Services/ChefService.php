<?php

namespace App\Services;
use App\Models\Purchase;
use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;
class ChefService
{
    public function getPurchase()
    {
        $chefId = auth()->id();

        $purchases = Purchase::with(['user.userInfo', 'recipe'])
            ->whereHas('recipe', function ($query) use ($chefId) {
                $query->where('userID', $chefId);
            })
            ->get();

        return $purchases;
    }
    public function getChefOwnedCertificates()
    {
        $user = Auth::user();

        if ($user && $user->role === 'chef') {
            return $user->certificates; // uses certificates() relation in User model
        }

        return collect();
    }

}
