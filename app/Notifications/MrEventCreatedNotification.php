<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MrEventCreatedNotification extends Notification
{
    use Queueable;

    protected $event;

    /**
    * Create a new notification instance.
    */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
    * Notification channels
    */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
    * Store notification in database
    */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id'    => $this->event->id,
            'title'       => null,
            'assigned_by' => auth()->user()->name,
            'message'     => auth()->user()->name  . ' has submitted a new event for approval: '  . $this->event->title,
            'url'         => url('manager/events-waiting-for-approval'),
            'icon'        => 'fas fa-calendar-alt',
        ];
    }
}
