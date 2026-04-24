<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public static function send(int $userId, string $type, string $title, string $message, string $link = null): void
    {
        Notification::create([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'link'    => $link,
            'is_read' => false,
        ]);
    }

    public static function sendToAll(string $role, string $type, string $title, string $message, string $link = null): void
    {
        \App\Models\User::where('role', $role)
            ->where('status', 'active')
            ->each(function($user) use ($type, $title, $message, $link) {
                static::send($user->id, $type, $title, $message, $link);
            });
    }
}