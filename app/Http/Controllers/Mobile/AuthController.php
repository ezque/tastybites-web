<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    public function register(Request $request)
    {
        \Log::info('register request data:', $request->all());

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
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create user
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Use 'role' to match validator
            'status' => 'active',
        ]);

        $certificatePath = null;

        // Save certificate if user is chef
        if ($request->role === 'chef' && $request->hasFile('credentials')) {
            $certificatePath = $request->file('credentials')->store('certificates', 'public');
        }

        // Create user info
        UserInfo::create([
            'userID' => $user->id,
            'userName' => $request->userName,
            'fullName' => $request->fullName,
            'experience' => $request->role === 'chef' ? $request->experience : null,
            'credentials' => $certificatePath,
        ]);

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user,
        ], 201);
    }


}
