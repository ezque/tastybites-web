<?php

namespace App\Services;
use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Purchase;
class RecipeService
{
    public function getRecipeCardDetails()
    {
        $recipe = Recipe::with([
            'user.userInfo',
            'purchase.user'
        ])
            ->select('id', 'recipeName', 'price', 'cuisineType', 'status', 'image_path', 'userID', 'is_free')
            ->get();

        return $recipe;
    }

    public function getAllRecipeDetails()
    {
        $userId = auth()->id();

        $recipes = Recipe::with(['user.userInfo'])
            ->with([
                // Always eager load purchase for current user
                'purchase' => function ($query) use ($userId) {
                    $query->where('userID', $userId)
                        ->where('status', 'confirmed');
                }
            ])
            ->get();

        // Attach ingredients + procedures conditionally
        foreach ($recipes as $recipe) {
            if ($recipe->is_free || $recipe->purchase || $recipe->userID == $userId) {
                $recipe->load(['ingredient', 'procedure']);
            }
        }

        return $recipes;
    }






}
