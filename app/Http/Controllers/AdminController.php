<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Services\UserService;
use App\Models\User;
use App\Services\RecipeService;
use App\Services\ChefService;
use App\Services\NotificationServices;
use App\Models\Recipe;

class AdminController extends Controller
{
    public function dashboard(UserService $userService, RecipeService $recipeService, NotificationServices $notificationServices): \Inertia\Response
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
            ]
        );
    }

    public function acceptChef(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->update(['status' => 'active']);

        return response()->json([
            'status' => 'success',
            'message' => 'Chef accepted successfully.'
        ]);
    }
    public function rejectChef(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->update(['status' => 'inactive']);

        return response()->json([
            'status' => 'success',
            'message' => 'Chef rejected successfully.'
        ]);

    }
    public function updateUserStatus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        if (strtolower($user->status) !== 'blocked') {
            // Block the user
            $user->update(['status' => 'blocked']);

            return response()->json([
                'status' => 'success',
                'message' => 'User blocked successfully.',
                'new_status' => $user->status
            ]);
        } else {
            // Unblock the user
            $user->update(['status' => 'active']); // <-- lowercase for consistency

            return response()->json([
                'status' => 'success',
                'message' => 'User unblocked successfully.',
                'new_status' => $user->status
            ]);
        }
    }

    public function updateChefRecipeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,declined',
        ]);

        $recipe = Recipe::findOrFail($id);
        $recipe->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Recipe status updated to {$request->status}.",
            'recipe' => $recipe,
        ]);
    }



}
