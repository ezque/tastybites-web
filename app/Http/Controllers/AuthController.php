<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;
use App\Models\UserInfo;

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

        $credentialsPath = null;
        if ($request->hasFile('credentials')) {
            $credentialsPath = $request->file('credentials')->store('credentials', 'public');
        }


        UserInfo::create([
            'userID' => $user->id,
            'fullName' => $request->fullName,
            'userName' => $request->userName,
            'profilePath' => null,
            'coverPath' => null,
            'experience' => $request->role === 'chef' ? $request->experience : null,
            'credentials' => $credentialsPath,
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
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($user->status !== 'active') {
            return response()->json(['message' => 'Your account is not active.'], 403);
        }

        $redirectUrl = match ($user->role) {
            'admin' => '/admin-dashboard',
            'chef'  => '/chef-dashboard',
            default => '/user-dashboard',
        };

        // Check mode
        $mode = $request->input('mode', 'web');

        if ($mode === 'api') {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful.',
                'token' => $token,
                'redirect_url' => $redirectUrl,
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'role' => $user->role,
                ]
            ]);
        }

        // Session-based login for web
        Auth::login($user); // OR Auth::attempt($credentials)
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Login successful.',
            'redirect_url' => $redirectUrl,
        ]);
    }

}
