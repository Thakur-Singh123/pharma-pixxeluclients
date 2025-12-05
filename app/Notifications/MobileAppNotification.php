<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class MobileAppNotification extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $meta;

    public function __construct($title, $message, array $meta = [])
    {
        $this->title   = $title;
        $this->message = $message;
        $this->meta    = $meta;
    }

    public function via($notifiable)
    {
        // DATABASE + BROADCAST (Real-time for web)
        return ['database', 'broadcast'];
    }

    // Save to database
    public function toDatabase($notifiable)
    {
        return [
            "title"   => $this->title,
            "message" => $this->message,
            "url"     => $this->meta['url']  ?? url('/'),
            "type"    => $this->meta['type'] ?? null,
            "id"      => $this->meta['id']   ?? null,
            "icon"    => $this->meta['icon'] ?? "fas fa-bell"
        ];
    }

    // Web broadcasting requires this
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    // For Web Real-Time data
    public function toArray($notifiable)
    {
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
