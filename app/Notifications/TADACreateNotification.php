<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TADACreateNotification extends Notification
{
    use Queueable;

  protected $tada;

    /**
     * Create a new notification instance.
     */
    public function __construct($tada)
    {
        $this->tada = $tada;
    }
    public function via(object $notifiable): array
    {
        return ['database']; 
    }

    public function toArray(object $notifiable): array
    {
        return [
            'tada_id'     => $this->tada->id,
            'title'       => null,
            'assigned_by' => auth()->user()->name,
            'message'     => auth()->user()->name . ' has created a new TADA with amount of INR: ' . $this->tada->total_amount,
            'url'         => url('manager/tada-records'),
            'icon'        => 'fas fa-dollar-sign',
        ];
    }

}
