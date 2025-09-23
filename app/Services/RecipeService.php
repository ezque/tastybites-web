<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Purchase;
use App\Models\Reaction;
class RecipeService
{
    public function getRecipeCardDetails()
    {
        $userId = auth()->id();

        $recipes = Recipe::with([
            'user.userInfo',
            'purchase.user',
            'userReaction',
            'hidden' => function ($q) use ($userId) {
                $q->where('userID', $userId);
            },
            'savedBy' => function ($q) use ($userId) {
                $q->where('userID', $userId);
            },
        ])
            ->select('id', 'recipeName', 'price', 'cuisineType', 'status', 'image_path', 'userID', 'is_free')
            ->get()
            ->map(function ($recipe) use ($userId) {
                $recipe->reaction_type = $recipe->userReaction->reaction_type ?? null;
                $recipe->userReactedLike = $recipe->reaction_type === '1';
                $recipe->userReactedDislike = $recipe->reaction_type === '2';

                $recipe->is_hidden = $recipe->hidden?->is_hidden ?? '0';
                $recipe->is_saved = $recipe->savedBy?->save_status ?? '0';

                $recipe->is_owned = $recipe->userID === $userId;

                return $recipe;
            });

        return $recipes;
    }



    public function getAllRecipeDetails()
    {
        $userId = auth()->id();

        $recipes = Recipe::with(['user.userInfo'])
            ->with([
                'purchase' => function ($query) use ($userId) {
                    $query->where('userID', $userId);
                },
                'userReaction' => function ($query) use ($userId) {
                    $query->where('userID', $userId);
                }
            ])
            ->get()
            ->map(function ($recipe) {
                $recipe->reaction_type = $recipe->userReaction->reaction_type ?? null;
                $recipe->userReactedLike = $recipe->reaction_type === '1';
                $recipe->userReactedDislike = $recipe->reaction_type === '2';
                return $recipe;
            });

        foreach ($recipes as $recipe) {
            if ($recipe->is_free || ($recipe->purchase && $recipe->purchase->status === 'confirmed') || $recipe->userID == $userId) {
                $recipe->load(['ingredient', 'procedure']);
            }
        }

        return $recipes;
    }

    public function getRecipeDetailsAdmin()
    {
        return Recipe::with(['ingredient', 'procedure', 'user.userInfo'])
            ->where('is_free', 'premium')
            ->where('status', 'pending')
            ->get();
    }

    public function getAdminTotalIncome()
    {
        $monthly = Recipe::selectRaw('MONTH(created_at) as month, SUM(price * 0.10) as total')
            ->where('status', 'active')
            ->where('is_free', 'premium')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $yearly = Recipe::selectRaw('YEAR(created_at) as year, SUM(price * 0.10) as total')
            ->where('status', 'active')
            ->where('is_free', 'premium')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        $totalIncome = Recipe::where('status', 'active')
            ->where('is_free', 'premium')
            ->sum(DB::raw('price * 0.10'));

        return [
            'total' => $totalIncome,
            'monthly' => $monthly,
            'yearly' => $yearly
        ];
    }











}
