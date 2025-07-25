<?php

namespace App\Services;
use Illuminate\Http\Request;
use App\Models\Recipe;
class RecipeService
{
    public function getRecipeCardDetails()
    {
        return Recipe::with(['user.userInfo'])
        ->select('id', 'recipeName', 'price', 'cuisineType', 'status', 'image_path', 'userID')
            ->get();
    }
}
