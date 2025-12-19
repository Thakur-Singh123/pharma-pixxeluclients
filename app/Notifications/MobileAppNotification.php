<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class MobileAppNotification extends Notification
{
    public $title;
    public $message;
    public $meta;

    //Function for construct
    public function __construct($title, $message, array $meta = []) {
        $this->title   = $title;
        $this->message = $message;
        $this->meta    = $meta;
    }

    //Function for db
    public function via($notifiable) {
        return ['database'];
        
    }

    //Function for db
    public function toDatabase($notifiable) {
        return [
            "title"   => $this->title,
            "message" => $this->message,
            "url"     => $this->meta['url']  ?? url('/'),
            "type"    => $this->meta['type'] ?? null,
            "id"      => $this->meta['id']   ?? null,
            "icon"    => $this->meta['icon'] ?? "fas fa-bell"
        ];
    }
}
