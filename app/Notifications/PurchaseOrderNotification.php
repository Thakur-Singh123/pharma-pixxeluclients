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
            'message'=> 'A new purchase order has been created by ' . auth()->user()->name,
            'url'     => url('manager/purchase-manager/' . $this->po->id . '/edit'),
            'icon'   => 'fas fa-shopping-cart',
        ];
    }
}
