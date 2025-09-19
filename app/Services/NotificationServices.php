<?php

namespace App\Services;
use App\Models\Notification;
class NotificationServices
{

    public function getNotification()
    {
        $user = auth()->user();

        if (!$user) {
            return collect();
        }

        return $user->notifications()
            ->orderBy('created_at', 'desc')
            ->get();
    }

}
