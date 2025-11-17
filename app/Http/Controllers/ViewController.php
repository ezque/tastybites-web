<?php

namespace App\Http\Controllers;

use App\Services\ChefService;
use App\Services\NotificationServices;
use App\Services\RecipeService;
use App\Services\UserService;
use App\Services\ReportServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ViewController extends Controller
{
    public function adminDashboard(UserService $userService, RecipeService $recipeService, NotificationServices $notificationServices, ReportServices $reportServices): \Inertia\Response
    {
        $user = Auth::user()->load('userInfo');
        $chefs = $userService->getChefInfo();
        $usersInfo = $userService->getUserInfo();
        $recipeAllDetails = $recipeService->getAllRecipeDetails();
        $recipeCardDetails = $recipeService->getRecipeCardDetails();
        $getRecipeDetailsAdmin = $recipeService->getRecipeDetailsAdmin();
        $getNotification = $notificationServices->getNotification();
        $adminTotalIncome = $recipeService->getAdminTotalIncome();
        $totalCountsUsers = $userService->totalCountsUsers();
        $getTotalRecipeCounts = $recipeService->getTotalRecipeCounts();
        $getAllReports = $reportServices->getAllReports();

        return Inertia::render('Admin/Dashboard',
            [
                'user' => $user,
                'recipeCardDetails' => $recipeCardDetails,
                'chefs' => $chefs,
                'usersInfo' => $usersInfo,
                'recipeAllDetails' => $recipeAllDetails,
                'getRecipeDetailsAdmin' => $getRecipeDetailsAdmin,
                'getNotification' => $getNotification,
                'adminTotalIncome' => $adminTotalIncome,
                'totalCountsUsers' => $totalCountsUsers,
                'getTotalRecipeCounts' => $getTotalRecipeCounts,
                'allReports' => $getAllReports,
            ]
        );
    }
    public function userDashboard(RecipeService $recipeService, NotificationServices $notificationServices, UserService $userService): \Inertia\Response
    {
        $user = Auth::user()->load('userInfo');

        $recipeAllDetails = $recipeService->getAllRecipeDetails();
        $getNotification = $notificationServices->getNotification();
        $chefs = $userService->getChefInfo();

        return Inertia::render('User/Dashboard',
            [
                'user' => $user,
                'chefs' => $chefs,
                'recipeAllDetails' => $recipeAllDetails,
                'getNotification' => $getNotification
            ]
        );
    }
    public function chefDashboard(Request $request, RecipeService $recipeService, ChefService $chefService, NotificationServices $notificationServices, UserService $userService): \Inertia\Response
    {
        $user = Auth::user()->load('userInfo');

        $recipeCardDetails = $recipeService->getRecipeCardDetails();
        $recipeAllDetails = $recipeService->getAllRecipeDetails();
        $purchases = $chefService->getPurchase();
        $getNotification = $notificationServices->getNotification();
        $chefCertificate = $chefService->getChefOwnedCertificates();
        $chefs = $userService->getChefInfo();

        $chefId = $user->id;
        $chefTotalIncome = $chefService->getChefTotalEarnings($chefId);

        return Inertia::render('Chef/Dashboard', [
            'user' => $user,
            'chefs' => $chefs,
            'recipeCardDetails' => $recipeCardDetails,
            'recipeAllDetails' => $recipeAllDetails,
            'purchases' => $purchases,
            'getNotification' => $getNotification,
            'chefCertificate' => $chefCertificate,
            'chefTotalIncome' => $chefTotalIncome,
        ]);
    }
}
