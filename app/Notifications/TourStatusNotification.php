<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TourStatusNotification extends Notification
{
    use Queueable;

    protected $tour_plan;

    public function __construct($tour_plan)
    {
        $this->tour_plan = $tour_plan;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $status = $this->tour_plan->approval_status; 
        $message = '';
        $icon = '';

        if($status === 'Approved') {
            $message = "Your tour plan titled '{$this->tour_plan->title}' has been approved by the manager.";
            $icon = 'fas fa-plane';
        } elseif($status === 'Rejected') {
            $message = "Oops! Your tour plan titled '{$this->tour_plan->title}' has been rejected by the manager. Please review the plan, make necessary changes, and resend it for approval.";
            $icon = 'fas fa-times-circle text-danger';
        }

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
