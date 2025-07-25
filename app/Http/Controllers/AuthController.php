<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function login(): \Inertia\Response
    {
        return Inertia::render('Auth/Login');
    }
    public function register(): \Inertia\Response
    {
        return Inertia::render('Auth/Register');
    }

    public function registerPost(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'fullName' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'userName' => 'required|string|max:255|unique:user_info,userName',
            'role' => 'required|in:user,chef',
            'experience' => 'required_if:role,chef|string|nullable',
            'credentials' => 'required_if:role,chef|file|nullable',
        ]);



        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => $request->role === 'chef' ? 'pending' : 'active',
            'role' => $request->role,
        ]);

        UserInfo::create([
            'userID' => $user->id,
            'fullName' => $request->fullName,
            'userName' => $request->userName,
            'gender' => $request->gender,
            'profilePath' => null,
            'coverPath' => null,
            'experience' => $request->role === 'chef' ? $request->experience : null,
            'credentials' => $request->role === 'chef' ? $request->credentials : null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully.',
        ]);
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        $remember = $request->has('remember'); // important if you're handling "remember me"

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            $redirect = match ($user->role ?? 'user') {
                'admin' => '/admin-dashboard',
                'chef' => '/chef-dashboard',
                default => '/user-dashboard',
            };

            return response()->json(['redirect' => $redirect]);
        }

        return response()->json([
            'errors' => ['email' => ['Invalid credentials.']],
        ], 422);
    }


    public function logout(Request $request)
    {
        \Log::info('Logout route hit.');

        Auth::logout(); // Logout the user

        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate CSRF token

        return redirect('/'); // Redirect to home or login page
    }

}
