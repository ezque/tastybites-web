<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\RecipeService;
use Inertia\Inertia;
use App\Services\NotificationServices;


class UserController extends Controller
{
    public function dashboard(RecipeService $recipeService, NotificationServices $notificationServices, UserService $userService): \Inertia\Response
    {
        $user = Auth::user()->load('userInfo');

        $recipeCardDetails = $recipeService->getRecipeCardDetails();
        $recipeAllDetails = $recipeService->getAllRecipeDetails();
        $getNotification = $notificationServices->getNotification();
        $chefs = $userService->getChefInfo();

        return Inertia::render('User/Dashboard',
            [
                'user' => $user,
                'chefs' => $chefs,
                'recipeCardDetails' => $recipeCardDetails,
                'recipeAllDetails' => $recipeAllDetails,
                'getNotification' => $getNotification
            ]
        );
    }

}
