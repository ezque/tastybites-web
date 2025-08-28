<?php

namespace App\Services;
use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Purchase;
use App\Models\Reaction;
class RecipeService
{
    public function getRecipeCardDetails()
    {
        $recipes = Recipe::with([
            'user.userInfo',
            'purchase.user',
            'userReaction'
        ])
            ->select('id', 'recipeName', 'price', 'cuisineType', 'status', 'image_path', 'userID', 'is_free')
            ->get()
            ->map(function ($recipe) {
                $recipe->reaction_type = $recipe->userReaction->reaction_type ?? null;
                $recipe->userReactedLike = $recipe->reaction_type === '1';
                $recipe->userReactedDislike = $recipe->reaction_type === '2';
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
                    $query->where('userID', $userId); // get only the logged-in user’s reaction
                }
            ])
            ->get()
            ->map(function ($recipe) {
                // Add reaction type safely
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










}
