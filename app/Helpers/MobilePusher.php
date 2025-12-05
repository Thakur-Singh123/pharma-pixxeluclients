<?php

namespace App\Helpers;

use App\Events\MobileNotificationEvent;
use App\Models\User;
use App\Notifications\MobileAppNotification;

class MobilePusher
{
    //Function for details
    public static function send($userId, $title, $message, $type, $itemId) {
        //Get user
        $user = User::find($userId);
        if (!$user) return;
        //Icon
        $icon = \App\Helpers\NotificationIcon::get($type);
        $url = self::makeUrl($user, $type);
        //Save in DB
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
        //Real-time event
        event(new MobileNotificationEvent($userId, [
            "title"   => $title,
            "message" => $message,
            "type"    => $type,
            "id"      => $itemId,
            "url"     => $url,
            "icon"    => $icon
        ]));
    }

    //Function for maping
    protected static function makeUrl($user, $type) {
        //Get role
        $role = strtolower($user->user_type ?? 'mr');
        //url
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
        //Check if type exists or not
        if (isset($map[$role][$type])) {
            return url($map[$role][$type]);
        }
        return url("$role/$type");
    }
}
