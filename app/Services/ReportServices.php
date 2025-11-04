<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;

class ReportServices
{
    public function getAllReports()
    {
        $user = Auth::user();

        // Log current access
        Log::info('Checking admin access for getAllReports', [
            'user_id' => $user?->id,
            'role' => $user?->role,
        ]);

        // Ensure only admin can access
        if (!$user || $user->role !== 'admin') {
            Log::warning('Unauthorized access attempt to getAllReports', [
                'user_id' => $user?->id,
                'role' => $user?->role,
            ]);
            return collect(); // Return empty collection instead of aborting
        }

        Log::info('Admin access granted for getAllReports', [
            'user_id' => $user->id,
            'role' => $user->role,
        ]);

        $allReports = Report::with([
            'reporter.userInfo:id,userID,fullName',
            'reportedUser.userInfo:id,userID,fullName',
            'reportedRecipe:id,recipeName',
        ])
            ->orderBy('created_at', 'desc')
            ->get([
                'id',
                'reporterID',
                'reportedUserID',
                'reportedRecipeID',
                'reason',
                'status',
            ])
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'reporter' => $report->reporter,
                    'reportedUser' => $report->reportedUser,
                    'reportedRecipe' => $report->reportedRecipe,
                    'reporterID' => $report->reporterID,
                    'reportedUserID' => $report->reportedUserID,
                    'reportedRecipeID' => $report->reportedRecipeID,
                    'reason' => $report->reason,
                    'status' => $report->status,
                ];
            });

        return $allReports;
    }

}
