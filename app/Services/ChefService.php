<?php

namespace App\Services;
use App\Models\Purchase;
class ChefService
{
    public function getPurchase()
    {
        $chefId = auth()->id();

        $purchases = Purchase::with(['user', 'recipe'])
            ->whereHas('recipe', function ($query) use ($chefId) {
                $query->where('userID', $chefId); // recipe created by this chef
            })
            ->get();

        return $purchases;
    }
}
