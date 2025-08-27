<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RecipeService;
use App\Services\ChefService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChefController extends Controller
{
    public function dashboard(Request $request, RecipeService $recipeService, ChefService $chefService): \Inertia\Response
    {
        $user = Auth::user()->load('userInfo');

        $recipeCardDetails = $recipeService->getRecipeCardDetails();
        $recipeAllDetails = $recipeService->getAllRecipeDetails();
        $purchases = $chefService->getPurchase();

        return Inertia::render('Chef/Dashboard', [
            'user' => $user,
            'recipeCardDetails' => $recipeCardDetails,
            'recipeAllDetails' => $recipeAllDetails,
            'purchases' => $purchases
        ]);
    }
}
