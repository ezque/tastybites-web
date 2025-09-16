<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        \Log::info('Login request data:', $request->all());

        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            \Log::warning("Login failed for email: {$request->email}");
            return response()->json([
                'message' => 'Invalid credentials',
                'field'   => 'email',
            ], 401);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        \Log::info("Login successful for user ID: {$user->id}, role: {$user->role}");

        return response()->json([
            'access_token' => $token,
            'role'         => $user->role,
            'id'           => $user->id,
        ]);
    }

}
