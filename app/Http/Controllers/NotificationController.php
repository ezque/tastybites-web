<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function readAllNotifications()
    {
        $userId = Auth::id();

        Notification::where('userID', $userId)
            ->where('status', 'unread')
            ->update(['status' => 'read']);

        return response()->json(['message' => 'All notifications marked as read.']);
    }

    public function readNotification(Request $request, $id)
    {
        $userId = Auth::id();

        Notification::where('userID', $userId)
            ->where('id', $id)
            ->update(['status' => 'read']);

        return response()->json(['message' => 'Notification marked as read.']);
    }

}
