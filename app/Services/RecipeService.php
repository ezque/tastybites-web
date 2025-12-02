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

        // Fetch all recipes with related data
        $recipes = Recipe::with([
            'user.userInfo',
            'purchase.user', // current user's purchase
            'userReaction',  // current user's reaction
            'hidden' => fn($q) => $q->where('userID', $userId),
            'savedBy' => fn($q) => $q->where('userID', $userId),
        ])
            ->withCount([
                'reactions as likes_count' => fn($q) => $q->where('reaction_type', '1'),
            ])
            ->get()
            ->map(function ($recipe) use ($userId) {
                // User reaction
                $recipe->reaction_type = $recipe->userReaction->reaction_type ?? null;
                $recipe->userReactedLike = $recipe->reaction_type === '1';
                $recipe->userReactedDislike = $recipe->reaction_type === '2';

                // Hidden and saved status
                $recipe->is_hidden = $recipe->hidden?->is_hidden ?? 0;
                $recipe->is_saved = $recipe->savedBy?->save_status ?? 0;

                // Ownership
                $recipe->is_owned = $recipe->userID === $userId;

                // Purchase status
                $recipe->purchase_status = $recipe->purchase?->status; // 'pending', 'confirmed', or null
                $recipe->is_purchased = $recipe->purchase_status === 'confirmed';
                $recipe->is_pending_purchase = $recipe->purchase_status === 'pending';

                // Access
                $recipe->can_access = $recipe->is_owned || $recipe->is_purchased || $recipe->is_free;

                // ⭐ Ratings
                $recipe->average_rating = $recipe->average_rating;
                $recipe->ratings_count = $recipe->ratings_count;

                // Load full recipe data if allowed
                if ($recipe->is_free || $recipe->is_purchased || $recipe->is_owned) {
                    $recipe->load(['ingredient', 'procedure']);
                }

                return $recipe;
            });

        // Top liked recipes
        $topLiked = $recipes
            ->sortByDesc('likes_count')
            ->take(5)
            ->values();

        // Top purchased premium recipes
        $topPurchasedPremium = Recipe::with([
            'user.userInfo',
            'allPurchases.user',
            'ingredient',
            'procedure',
            'userReaction' => fn($q) => $q->where('userID', $userId),
            'hidden' => fn($q) => $q->where('userID', $userId),
            'savedBy' => fn($q) => $q->where('userID', $userId),
        ])
            ->withCount([
                'allPurchases as purchase_count' => fn($q) => $q->where('status', 'confirmed'),
            ])
            ->where('is_free', 'premium')
            ->orderByDesc('purchase_count')
            ->take(5)
            ->get()
            ->map(function ($recipe) use ($userId) {
                $recipe->reaction_type = $recipe->userReaction->reaction_type ?? null;
                $recipe->userReactedLike = $recipe->reaction_type === '1';
                $recipe->userReactedDislike = $recipe->reaction_type === '2';

                $recipe->is_hidden = $recipe->hidden?->is_hidden ?? 0;
                $recipe->is_saved = $recipe->savedBy?->save_status ?? 0;
                $recipe->is_owned = $recipe->userID === $userId;

                $recipe->purchase_status = $recipe->purchase?->status;
                $recipe->is_purchased = $recipe->purchase_status === 'confirmed';
                $recipe->is_pending_purchase = $recipe->purchase_status === 'pending';

                // ⭐ Ratings
                $recipe->average_rating = $recipe->average_rating;
                $recipe->ratings_count = $recipe->ratings_count;

                $recipe->can_access = $recipe->is_owned || $recipe->is_purchased || $recipe->is_free;

                return $recipe;
            });

        return [
            'all' => $recipes,
            'topLiked' => $topLiked,
            'topPurchased' => $topPurchasedPremium,
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
                // ⭐ Ratings
                $recipe->average_rating = $recipe->average_rating;
                $recipe->ratings_count = $recipe->ratings_count;
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

    public function getAllWhoPurchasedRecipe($recipeID)
    {
        try {
            $purchases = Purchase::with([
                'user',
                'user.userInfo'
            ])
                ->where('status', 'confirmed')
                ->where('recipeID', $recipeID)
                ->get();

            return response()->json([
                'status' => true,
                'purchases' => $purchases
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch purchase data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
















}
