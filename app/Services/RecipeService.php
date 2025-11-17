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

        $baseQuery = Recipe::with([
            'user.userInfo',
            'purchase.user',
            'userReaction',
            'hidden' => fn($q) => $q->where('userID', $userId),
            'savedBy' => fn($q) => $q->where('userID', $userId),
        ])
            ->select('id', 'recipeName', 'description', 'video_path', 'gcash_number', 'receipt_path', 'gCash_path',
                'price', 'cuisineType', 'status', 'image_path', 'userID', 'is_free')
            ->withCount([
                'reactions as likes_count' => fn($q) => $q->where('reaction_type', '1'),
                'purchase as purchase_count'
            ]);

        // All recipes
        $recipes = $baseQuery->inRandomOrder()->get()->map(function ($recipe) use ($userId) {
            $recipe->reaction_type = $recipe->userReaction->reaction_type ?? null;
            $recipe->userReactedLike = $recipe->reaction_type === '1';
            $recipe->userReactedDislike = $recipe->reaction_type === '2';

            $recipe->is_hidden = $recipe->hidden?->is_hidden ?? 0;
            $recipe->is_saved = $recipe->savedBy?->save_status ?? 0;
            $recipe->is_owned = $recipe->userID === $userId;
            $recipe->is_purchased = $recipe->purchase ? true : false;
            $recipe->can_access = $recipe->is_owned || $recipe->is_purchased || $recipe->is_free;

            if ($recipe->is_free || ($recipe->purchase && $recipe->purchase->status === 'confirmed') || $recipe->userID == $userId) {
                $recipe->load(['ingredient', 'procedure']);
            }

            return $recipe;
        });

        // Top 5 most liked recipes
        $topLiked = $recipes
            ->sortByDesc('likes_count')  // sort collection by likes
            ->take(5)                    // take top 5
            ->values();                  // reset keys

        // Top 5 most purchased recipes
        $topPurchased = $recipes
            ->sortByDesc('purchase_count')
            ->take(5)
            ->values();

        return [
            'all' => $recipes,
            'topLiked' => $topLiked,
            'topPurchased' => $topPurchased
        ];
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
    public function getTotalRecipeCounts()
    {
        return [
            'recipeCounts' => Recipe::where('status', 'active')->count()
        ];
    }











}
