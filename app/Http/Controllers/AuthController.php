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
use App\Models\Notification;

class AuthController extends Controller
{
    public function login(): \Inertia\Response|\Illuminate\Http\RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();

            $redirect = match ($user->role ?? 'user') {
                'admin' => '/admin-dashboard',
                'chef' => '/chef-dashboard',
                default => '/user-dashboard',
            };

            return redirect($redirect);
        }

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

        if ($request->hasFile('credentials')) {
            $credentialsPath = $request->file('credentials')->store('credentials', 'public');
        } else {
            $credentialsPath = null;
        }


        UserInfo::create([
            'userID' => $user->id,
            'fullName' => $request->fullName,
            'userName' => $request->userName,
            'gender' => $request->gender,
            'profilePath' => null,
            'coverPath' => null,
            'experience' => $request->role === 'chef' ? $request->experience : null,
            'credentials' => $request->role === 'chef' ? $credentialsPath : null,
        ]);
        if ($request->role === 'chef') {
            $admins = User::where('role', 'admin')->get();

            foreach ($admins as $admin) {
                Notification::create([
                    'userID'   => $admin->id,
                    'senderID' => $user->id,
                    'type'     => 'chefApplicant',
                    'message'  =>  " has signed up as a new chef. Review their profile.",
                ]);
            }
        }
        if ($request->role === 'user'){
            $admins = User::where('role', 'admin')->get();

            foreach ($admins as $admin) {
                Notification::create([
                    'userID'   => $admin->id,
                    'senderID' => $user->id,
                    'type'     => 'userApplicant',
                    'message' => " has joined the community."

                ]);
            }
        }



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

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return response()->json([
                'errors' => ['email' => ['Invalid credentials.']]
            ], 422);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        $redirect = match ($user->role ?? 'user') {
            'admin' => '/admin-dashboard',
            'chef' => '/chef-dashboard',
            default => '/user-dashboard',
        };

        return response()->json(['redirect' => $redirect]);
    }




    public function logout(Request $request)
    {
        \Log::info('Logout route hit.');

        Auth::logout(); // Logout the user

        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate CSRF token

        return redirect('/'); // Redirect to home or login page
    }

    public function editPersonalInformation(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'fullName' => 'nullable|string|max:255',
            'userName' => 'nullable|string|max:255|unique:user_info,userName,' . Auth::id() . ',userID',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $userInfo = $user->userInfo;


        if (isset($validated['fullName'])) {
            $userInfo->fullName = $validated['fullName'];
        }
        if (isset($validated['userName'])) {
            $userInfo->userName = $validated['userName'];
        }

        $userInfo->save();

        return response()->json([
            'success' => true,
            'message' => 'Personal information updated successfully.',
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

    public function updateProfilePicture(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'profile' => 'required|image|mimes:jpeg,png,jpg,gif|max:20048',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $userInfo = $user->userInfo;

        if ($userInfo->profilePath && Storage::disk('public')->exists($userInfo->profilePath)) {
            Storage::disk('public')->delete($userInfo->profilePath);
        }

        $path = $request->file('profile')->store('profiles', 'public');

        $userInfo->profilePath = $path;
        $userInfo->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile picture updated successfully.',
            'profilePath' => $path,
        ]);
    }




}
