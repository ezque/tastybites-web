<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Services\UserService;
use App\Models\User;
use App\Services\RecipeService;
use App\Services\ChefService;
use App\Services\NotificationServices;
use App\Models\Recipe;
use App\Models\Notification;
use App\Models\Report;


class AdminController extends Controller
{


    public function acceptChef(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->update(['status' => 'active']);

        $message = 'Congratulations! Your chef application has been accepted.';
        $type = 'chefApproved';

        $notification = Notification::create([
            'userID'   => $user->id,
            'senderID' => auth()->id(),
            'message'  => $message,
            'status'   => 'unread',
            'type'     => $type,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Chef accepted successfully.',
            'notification' => $notification
        ]);
    }

    public function rejectChef(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->update(['status' => 'inactive']);

        // Create notification
        $message = 'We’re sorry, but your chef application has been rejected.';
        $type = 'chefDisapproved';

        $notification = Notification::create([
            'userID'   => $user->id,
            'senderID' => auth()->id(),
            'message'  => $message,
            'status'   => 'unread',
            'type'     => $type,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Chef rejected successfully.',
            'notification' => $notification
        ]);
    }

    public function updateUserStatus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        if (strtolower($user->status) !== 'blocked') {
            // Block the user
            $user->update(['status' => 'blocked']);

            return response()->json([
                'status' => 'success',
                'message' => 'User blocked successfully.',
                'new_status' => $user->status
            ]);
        } else {
            // Unblock the user
            $user->update(['status' => 'active']); // <-- lowercase for consistency

            return response()->json([
                'status' => 'success',
                'message' => 'User unblocked successfully.',
                'new_status' => $user->status
            ]);
        }
    }

    public function updateChefRecipeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,declined',
        ]);

        $recipe = Recipe::findOrFail($id);
        $recipe->update([
            'status' => $request->status,
        ]);

        // Recipient: the chef who created the recipe
        $recipientId = $recipe->userID;

        // Sender: admin who reviewed it
        $senderId = auth()->id();

        // Notification message & type
        if ($request->status === 'active') {
            $message = "Your recipe '{$recipe->recipeName}' has been approved.";
            $type = 'premiumRecipeApproved';
        } else {
            $message = "Your recipe '{$recipe->recipeName}' has been declined.";
            $type = 'premiumRecipeDeclined';
        }

        // Create notification
        $notification = Notification::create([
            'userID'   => $recipientId,
            'senderID' => $senderId,
            'recipeID' => $recipe->id,
            'message'  => $message,
            'status'   => 'unread',
            'type'     => $type,
        ]);

        // Debug log
        Log::info('Notification created', $notification->toArray());

        return response()->json([
            'success' => true,
            'message' => "Recipe status updated to {$request->status}.",
            'recipe' => $recipe,
            'notification' => $notification,
        ]);
    }
    public function getReport($id)
    {
        $report = Report::findOrFail($id);
        return response()->json($report);
    }




}
