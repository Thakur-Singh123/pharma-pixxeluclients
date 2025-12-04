<?php

namespace App\Helpers;

use App\Events\MobileNotificationEvent;
use App\Models\User;
use App\Notifications\MobileAppNotification;

class MobilePusher
{
    public static function send($userId, $title, $message, $type, $itemId)
    {
        $user = User::find($userId);
        if (!$user) return;

        $icon = \App\Helpers\NotificationIcon::get($type);

        // AUTO URL FOR WEB + MOBILE (LIST PAGE ONLY)
        $url = self::makeUrl($user, $type);

        // Save in DB
        $user->notify(new MobileAppNotification(
            $title,
            $message,
            [
                "type" => $type,
                "id"   => $itemId,
                "url"  => $url,
                "icon" => $icon
            ]
        ));

        // Real-time event
        event(new MobileNotificationEvent($userId, [
            "title"   => $title,
            "message" => $message,
            "type"    => $type,
            "id"      => $itemId,
            "url"     => $url,
            "icon"    => $icon
        ]));
    }

    // 🔥 FINAL URL MAPPING LOGIC
    protected static function makeUrl($user, $type)
    {
        $role = strtolower($user->user_type ?? 'mr');

        $map = [
            'mr' => [
                'tada'  => 'mr/tada',
                'task'  => 'mr/task',
                'daily' => 'mr/daily',
            ],
            'manager' => [
                'tada'  => 'manager/tada-records',
                'task'  => 'manager/task-list',
                'daily' => 'manager/daily-reports',
            ],
        ];

        if (isset($map[$role][$type])) {
            return url($map[$role][$type]);
        }

        return url("$role/$type");
    }
}
