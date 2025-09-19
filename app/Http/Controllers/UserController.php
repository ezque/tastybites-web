<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\RecipeService;
use Inertia\Inertia;
use App\Services\NotificationServices;


class UserController extends Controller
{
    public function dashboard(RecipeService $recipeService, NotificationServices $notificationServices): \Inertia\Response
    {
        $user = Auth::user()->load('userInfo');

        $recipeCardDetails = $recipeService->getRecipeCardDetails();
        $recipeAllDetails = $recipeService->getAllRecipeDetails();
        $getNotification = $notificationServices->getNotification();

        return Inertia::render('User/Dashboard',
            [
                'user' => $user,
                'recipeCardDetails' => $recipeCardDetails,
                'recipeAllDetails' => $recipeAllDetails,
                'getNotification' => $getNotification
            ]
        );
    }

}
