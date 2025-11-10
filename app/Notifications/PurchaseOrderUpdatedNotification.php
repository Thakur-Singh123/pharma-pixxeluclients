<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PurchaseOrderUpdatedNotification extends Notification
{
    use Queueable;

    protected $po;

    public function __construct($po)
    {
        $this->po = $po;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'po_id'   => $this->po->id,
            'title'   => 'Purchase Order Updated',
            'message' => 'Purchase order (#' . $this->po->id . ') has been updated by ' 
                         . auth()->user()->name . '. Please review the latest changes and take the necessary action if required.',
            'url'     => url('manager/purchase-manager'),
            'icon'    => 'fas fa-edit',
        ];
    }
}
