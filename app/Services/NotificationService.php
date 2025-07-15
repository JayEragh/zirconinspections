<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Create a notification for a user.
     */
    public static function create($userId, $type, $title, $message, $link = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
        ]);
    }

    /**
     * Create notification for multiple users.
     */
    public static function createForUsers($userIds, $type, $title, $message, $link = null)
    {
        $notifications = [];
        foreach ($userIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'link' => $link,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        return Notification::insert($notifications);
    }

    /**
     * Create notification for all users of a specific role.
     */
    public static function createForRole($role, $type, $title, $message, $link = null)
    {
        $userIds = User::where('role', $role)->pluck('id')->toArray();
        return self::createForUsers($userIds, $type, $title, $message, $link);
    }

    /**
     * Mark notification as read.
     */
    public static function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->update([
                'read' => true,
                'read_at' => now(),
            ]);
        }
        return $notification;
    }

    /**
     * Mark all notifications as read for a user.
     */
    public static function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('read', false)
            ->update([
                'read' => true,
                'read_at' => now(),
            ]);
    }
} 