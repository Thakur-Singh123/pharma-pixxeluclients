<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TourPlanNotification extends Notification
{
    use Queueable;
    
    protected $tourPlan; // Only tour plan

    public function __construct($tourPlan) {
        $this->tourPlan = $tourPlan;
    }

    public function via(object $notifiable): array {
        return ['database'];
    }

    public function toArray(object $notifiable): array {
        return [
            'tour_id' => $this->tourPlan->id,
            'title' => $this->tourPlan->title,
            'message' => auth()->user()->name . " has submitted a new tour plan titled '{$this->tourPlan->title}' for your review and approval. Please check the details and take action.",
            'url' => url('manager/tour-plans'),
            'icon' => 'fas fa-plane',
        ];
    }
}
