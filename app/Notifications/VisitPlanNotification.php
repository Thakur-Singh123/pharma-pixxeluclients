<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VisitPlanNotification extends Notification
{
    use Queueable;

    protected $plan;

    /**
     * Create a new notification instance.
     */
    public function __construct($plan)
    {
        $this->plan = $plan;
    }
    public function via(object $notifiable): array
    {
        return ['database']; 
    }


    public function toArray(object $notifiable): array
    {
        return [
            'task_id'   => $this->plan->id,
            'title'     => $this->plan->title,
            'assigned_by' => auth()->user()->name,
            'message'   => "Manager created new visit plan: {$this->plan->title}",
            'url'       => url('mr/visit-plans'),
            'icon' => 'fas fa-map-marker-alt',
        ];
    }
}
