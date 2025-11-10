<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PurchaseOrderApprovedNotification extends Notification
{
    use Queueable;
    
    //protected
    protected $po;
    protected $type; 

    //Function for construct
    public function __construct($po, $type = 'purchase_manager')  {
        $this->po = $po;
        $this->type = $type;
    }

    //Function for notificatino
    public function via($notifiable) {
        return ['database'];
    }

    //Function for url and notification
    public function toArray($notifiable)  {
        //URL
        $url = $this->type === 'vendor'
            ? url('vendor/purchase-orders')
            : url('purchase-manager/purchase-orders/' . $this->po->id . '/edit');

        return [
            'po_id'   => $this->po->id,
            'title'   => 'Purchase Order Approved',
            'message' => 'Your purchase order (ID: ' . $this->po->id . ') has been approved by Manager ' . auth()->user()->name,
            'url'     => $url,
            'icon'    => 'fas fa-check-circle',
        ];
    }
}
