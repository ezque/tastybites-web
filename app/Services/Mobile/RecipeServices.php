<?php

namespace App\Services\Mobile;

use App\Models\Recipe;

class RecipeServices
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
            ->withCount([
                'userReaction as total_likes' => function ($q) {
                    $q->where('reaction_type', '1');
                },
                'userReaction as total_dislikes' => function ($q) {
                    $q->where('reaction_type', '2');
                }
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

                $recipe->image_url = $recipe->image_path
                    ? asset('storage/' . $recipe->image_path)
                    : null;

                return $recipe;
            });

        return $recipes;
    }


}
