<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\RecipeRating;
use App\Models\User;
use App\Models\Recipe;
class RatingController extends Controller
{
    public function getUserRateForRecipe($id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['rating' => null]); // ← JSON + proper response
        }

        $recipe = Recipe::with('userRating')->find($id);

        if (!$recipe) {
            return response()->json(['rating' => null]);
        }

        $userRating = $recipe->userRating?->rating ?? null;

        return response()->json(['rating' => $userRating]); // ← Always return JSON
    }
    public function rateRecipe(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $rating = RecipeRating::updateOrCreate(
            [
                'recipe_id' => $id,
                'user_id'   => $user->id,
            ],
            [
                'rating' => $request->rating,
            ]
        );
        return response()->json([
            'message'        => 'Rating saved successfully',
            'user_rating'    => $request->rating,
        ]);
    }
}
