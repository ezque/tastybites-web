<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RecipeService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChefController extends Controller
{
    public function dashboard(Request $request, RecipeService $recipeService): \Inertia\Response
    {
        $user = Auth::user()->load('userInfo');

        $recipeCardDetails = $recipeService->getRecipeCardDetails();

        return Inertia::render('Chef/Dashboard', [
            'user' => $user,
            'recipeCardDetails' => $recipeCardDetails,
        ]);
    }
}
