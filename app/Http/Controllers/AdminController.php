<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard(): \Inertia\Response
    {
        $user = Auth::user()->load('userInfo');

        return Inertia::render('Admin/Dashboard',
            ['user' => $user]
        );
    }
}
