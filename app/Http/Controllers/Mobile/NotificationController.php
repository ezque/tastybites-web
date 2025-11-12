<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function notification()
    {
        $user = auth()->user();

        if (!$user) {
            return collect();
        }
        return $user->notifications()
            ->with('sender.userInfo')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
