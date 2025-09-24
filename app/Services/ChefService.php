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
            return $user->certificates;
        }

        return collect();
    }
    public function getChefTotalEarnings($chefId)
    {
        $totalEarnings = Purchase::whereHas('recipe', function ($query) use ($chefId) {
            $query->where('userID', $chefId);
        })
            ->where('status', 'confirmed')
            ->sum('amount');

        $monthlyEarnings = Purchase::whereHas('recipe', function ($query) use ($chefId) {
            $query->where('userID', $chefId);
        })
            ->where('status', 'confirmed')
            ->selectRaw('YEAR(purchase_at) as year, MONTH(purchase_at) as month, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $yearlyEarnings = Purchase::whereHas('recipe', function ($query) use ($chefId) {
            $query->where('userID', $chefId);
        })
            ->where('status', 'confirmed')
            ->selectRaw('YEAR(purchase_at) as year, SUM(amount) as total')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'total'   => $totalEarnings,
            'monthly' => $monthlyEarnings,
            'yearly'  => $yearlyEarnings,
        ];
    }



}
