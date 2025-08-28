<?php

namespace App\Services;
use App\Models\Purchase;
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
}
