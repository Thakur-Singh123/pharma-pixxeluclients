<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TourStatusNotification extends Notification
{
    use Queueable;

    //Get tour plan 
    protected $tour_plan;
    
    //Function for construct
    public function __construct($tour_plan) {
        $this->tour_plan = $tour_plan;
    }

    //Function for db notification
    public function via(object $notifiable): array {
        return ['database'];
    }
    
    //Function for get tour status
    public function toArray(object $notifiable): array  {
        //Get status
        $status = $this->tour_plan->approval_status; 
        $message = '';
        $icon = '';
        //Check if status approved or not
        if($status === 'Approved') {
            $message = "Your tour plan titled '{$this->tour_plan->title}' has been approved by the manager.";
            $icon = 'fas fa-plane';
        //Check if status rejected or not
        } elseif($status === 'Rejected') {
            $message = "Oops! Your tour plan titled '{$this->tour_plan->title}' has been rejected by the manager. Please review the plan, make necessary changes, and resend it for approval.";
            $icon = 'fas fa-times-circle text-danger';
        }
        //Get data
        return [
            'tour_id' => $this->tour_plan->id,
            'title' => $this->tour_plan->title,
            'status' => $status,
            'message' => $message,
            'url' => url('mr/updated-tour-plans'),
            'icon' => $icon,
        ];
    }
}
