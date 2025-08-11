<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Services\UserService;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard(UserService $userService): \Inertia\Response
    {
        $user = Auth::user()->load('userInfo');
        $chefs = $userService->getChefInfo();

        return Inertia::render('Admin/Dashboard',
            [
                'user' => $user,
                'chefs' => $chefs
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
}
