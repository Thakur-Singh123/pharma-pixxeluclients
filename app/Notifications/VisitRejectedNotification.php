<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VisitRejectedNotification extends Notification
{
   
    use Queueable;
    protected $visit_record;

    /**
    * Create a new notification instance.
    */

    //Function for construct
    public function __construct($visit_record) {
        //Get visit detail
        $this->visit_record = $visit_record;
    }

    //Function for get db
    public function via(object $notifiable): array {
        return ['database']; 
    }

    //Function for get records
    public function toArray(object $notifiable): array {
        return [
            'visit_id' => $this->visit_record->id,
            'message' => 'Your visit scheduled to "' . $this->visit_record->area_name . '" on ' . $this->visit_record->visit_date . ' has been rejected. Please review the details and resubmit the visit for approval.',
            'url' => url('mr/visits'),
            'icon' => 'fas fa-times-circle', 
            'type'   => 'visit_rejected',  
        ];
    }
}
