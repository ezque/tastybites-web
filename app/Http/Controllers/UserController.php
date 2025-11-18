<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Recipe;
use App\Models\Report;
use App\Models\User;
use App\Models\Follow;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\RecipeService;
use Inertia\Inertia;
use App\Services\NotificationServices;


class UserController extends Controller
{

    public function reportChef(Request $request, $id)
    {
        $request->validate([
            'type'   => 'required|in:user,recipe',
            'reason' => 'required|string|max:1000',
        ]);

        $reportData = [
            'reporterID'       => auth()->id(),
            'reportedUserID'   => null,
            'reportedRecipeID' => null,
            'reason'           => $request->reason,
            'status'           => 'pending',
        ];

        if ($request->type === 'user') {
            $reportData['reportedUserID'] = $id;
        } elseif ($request->type === 'recipe') {
            $reportData['reportedRecipeID'] = $id;
        }

        $report = Report::create($reportData);

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            if ($report->reportedUserID !== null) {
                Notification::create([
                    'userID'   => $admin->id,
                    'senderID' => auth()->id(),
                    'message'  => 'A user has been reported. Reporter ID: ' . auth()->id(),
                    'status'   => 'unread',
                    'type'     => 'report',
                    'reportID' => $report->id,
                ]);
            } elseif ($report->reportedRecipeID !== null) {
                $recipe = Recipe::find($report->reportedRecipeID);

                Notification::create([
                    'userID'   => $admin->id,
                    'senderID' => auth()->id(),
                    'message'  => 'A recipe "' . ($recipe->recipeName ?? 'Unknown') . '" has been reported.',
                    'status'   => 'unread',
                    'type'     => 'report',
                    'reportID' => $report->id,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Report submitted successfully.',
            'report'  => $report,
        ]);
    }
    public function follow(Request $request, $id)
    {
        $authUserId = auth()->id();
        $targetUserId = $id;

        // Cannot follow yourself
        if ($authUserId == $targetUserId) {
            return response()->json([
                'success' => false,
                'message' => "You cannot follow yourself."
            ], 400);
        }

        // Check if entry exists
        $follow = Follow::where('followerID', $authUserId)
            ->where('followedID', $targetUserId)
            ->first();

        // If follow already exists → toggle status
        if ($follow) {
            if ($follow->status === 'following') {
                $follow->status = 'unfollowed';
                $follow->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Unfollowed successfully.',
                    'status' => 'unfollowed'
                ]);
            } else {
                $follow->status = 'following';
                $follow->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Followed successfully.',
                    'status' => 'following'
                ]);
            }
        }

        // Create follow if not exists
        Follow::create([
            'followerID' => $authUserId,
            'followedID' => $targetUserId,
            'status' => 'following'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Followed successfully.',
            'status' => 'following'
        ]);
    }


}
