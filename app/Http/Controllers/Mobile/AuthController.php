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

        if (!$user) {
            // Email not registered
            \Log::warning("Login failed — email not registered: {$request->email}");
            return response()->json([
                'message' => 'Email not registered',
                'field'   => 'email',
            ], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            // Wrong password
            \Log::warning("Login failed — wrong password for email: {$request->email}");
            return response()->json([
                'message' => 'Incorrect password',
                'field'   => 'password',
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

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
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
    public function getPersonalInformation(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        // Load the user with the related userInfo
        $user->load('userInfo');

        return response()->json([
            'success' => true,
            'user' => $user,
        ]);
    }


    public function updateProfile(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $userInfo = $user->userInfo;

        // Validate inputs
        $validated = $request->validate([
            'fullName' => 'nullable|string|max:255',
            'userName' => 'nullable|string|max:255|unique:user_info,userName,' . $userInfo->userID . ',userID',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'profilePath' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20048', // image validation
        ]);

        // Update text fields if provided
        if (isset($validated['fullName'])) {
            $userInfo->fullName = $validated['fullName'];
        }
        if (isset($validated['userName'])) {
            $userInfo->userName = $validated['userName'];
        }
        if (isset($validated['gender'])) {
            $userInfo->gender = $validated['gender'];
        }

        // Handle profile image if uploaded
        if ($request->hasFile('profilePath')) {
            // Delete old image if exists
            if ($userInfo->profilePath && Storage::disk('public')->exists($userInfo->profilePath)) {
                Storage::disk('public')->delete($userInfo->profilePath);
            }

            // Store new image
            $path = $request->file('profilePath')->store('profiles', 'public');
            $userInfo->profilePath = $path;
        }

        $userInfo->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'user' => $user->load('userInfo'),
        ]);
    }

    public function changePassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.'
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }


}
