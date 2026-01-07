<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventAssignedNotification extends Notification
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
    public function via(object $notifiable): array
    {
        return ['database']; 
    }


    public function toArray(object $notifiable): array
    {
        return [
            'task_id'   => $this->event->id,
            'title'     => $this->event->title,
            'assigned_by' => auth()->user()->name,
            'message' => 'You have been assigned a new event by Manager ' . auth()->user()->name . ': ' . $this->event->title,
            'url'       => url('mr/events-assigned-by-manager'),
            'icon' => 'fas fa-calendar-check',
        ];
    }
}
