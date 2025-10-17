<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TourPlanNotification extends Notification
{
    use Queueable;
    
    //Get tour plan
    protected $tourPlan; 

    //Function for construct
    public function __construct($tourPlan) {
        $this->tourPlan = $tourPlan;
    }

    //Function for db notification
    public function via(object $notifiable): array {
        return ['database'];
    }
    
    //Function for get tour plan data
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
