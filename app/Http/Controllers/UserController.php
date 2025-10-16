<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Recipe;
use App\Models\Report;
use App\Models\User;
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
                ]);
            } elseif ($report->reportedRecipeID !== null) {
                $recipe = Recipe::find($report->reportedRecipeID);

                Notification::create([
                    'userID'   => $admin->id,
                    'senderID' => auth()->id(),
                    'message'  => 'A recipe "' . ($recipe->recipeName ?? 'Unknown') . '" has been reported.',
                    'status'   => 'unread',
                    'type'     => 'report',
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Report submitted successfully.',
            'report'  => $report,
        ]);
    }

}
