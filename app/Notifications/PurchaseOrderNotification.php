<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PurchaseOrderNotification extends Notification
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
            'po_id'  => $this->po->id,
            'title'  => 'New Purchase Order Created',
            'message' => 'A new purchase order (#' . $this->po->id . ') has been created by ' 
                . auth()->user()->name . '. Please review the order details and approve it if all information is correct.',
            'url' => url('manager/purchase-manager/'),
            'icon'   => 'fas fa-shopping-cart',
        ];
    }
}
