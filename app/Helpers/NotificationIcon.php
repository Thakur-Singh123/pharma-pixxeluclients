<?php

namespace App\Helpers;

class NotificationIcon
{
    public static function get($type)
    {
        $icons = [
            'tada'        => 'fas fa-route',
            'task'        => 'fas fa-tasks',
            'daily'       => 'fas fa-calendar-day',
            'plan'        => 'fas fa-clipboard-list',
            'approval'    => 'fas fa-check-circle',
            'rejected'    => 'fas fa-times-circle',
            'target'      => 'fas fa-bullseye',
            'notice'      => 'fas fa-bell',
            'meeting'     => 'fas fa-handshake',
        ];

        return $icons[$type] ?? 'fas fa-bell';  // default icon
    }
}
