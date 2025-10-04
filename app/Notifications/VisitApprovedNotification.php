<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VisitApprovedNotification extends Notification
{
    use Queueable;

    protected $visit_record;

    /**
     * Create a new notification instance.
    */
    //Function for construct
    public function __construct($visit_record) {
        //Get visit record
        $this->visit_record = $visit_record;
    }

    //Function for get db
    public function via(object $notifiable): array {
        return ['database']; 
    }

    //Function for send response
    public function toArray(object $notifiable): array {
        return [
            'visit_id' => $this->visit_record->id,
            'message' => 'Your visit to ' . $this->visit_record->area_name . ' on ' . $this->visit_record->visit_date . ' has been approved.',
            'url' => url('mr/visits'),
            'icon' => 'fas fa-check-circle', 
            'type' => 'visit_approved',
        ];
    }
}
