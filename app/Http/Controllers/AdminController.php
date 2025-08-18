<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Services\UserService;
use App\Models\User;
use App\Services\RecipeService;

class AdminController extends Controller
{
    public function dashboard(UserService $userService, RecipeService $recipeService): \Inertia\Response
    {
        $user = Auth::user()->load('userInfo');
        $chefs = $userService->getChefInfo();
        $usersInfo = $userService->getUserInfo();

        $recipeCardDetails = $recipeService->getRecipeCardDetails();

        return Inertia::render('Admin/Dashboard',
            [
                'user' => $user,
                'recipeCardDetails' => $recipeCardDetails,
                'chefs' => $chefs,
                'usersInfo' => $usersInfo
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
    public function blockUser(Request $request)
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
                'message' => 'User blocked successfully.'
            ]);
        } else {
            // Unblock the user
            $user->update(['status' => 'Active']);

            return response()->json([
                'status' => 'success',
                'message' => 'User unblocked successfully.'
            ]);
        }
    }

}
