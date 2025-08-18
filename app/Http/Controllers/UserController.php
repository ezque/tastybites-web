<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\RecipeService;
use Inertia\Inertia;


class UserController extends Controller
{
    public function dashboard(RecipeService $recipeService): \Inertia\Response
    {
        $user = Auth::user()->load('userInfo');

        $recipeCardDetails = $recipeService->getRecipeCardDetails();
        $recipeAllDetails = $recipeService->getAllRecipeDetails();

        return Inertia::render('User/Dashboard',
            [
                'user' => $user,
                'recipeCardDetails' => $recipeCardDetails,
                'recipeAllDetails' => $recipeAllDetails
            ]
        );
    }

}
