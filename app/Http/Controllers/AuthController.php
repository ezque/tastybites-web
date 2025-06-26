<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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

    public function loginPost(Request $request): \Illuminate\Http\JsonResponse
    {

    }
}
