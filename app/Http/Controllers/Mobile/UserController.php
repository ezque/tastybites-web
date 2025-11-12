<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getChefs()
    {
        $chefs = User::where('role', 'chef')
            ->with('userInfo')
            ->get();
        return response()->json($chefs);
    }
}
