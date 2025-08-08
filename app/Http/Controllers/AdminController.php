<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Services\UserService;

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
}
