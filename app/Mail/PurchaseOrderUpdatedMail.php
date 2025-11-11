<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\PurchaseOrder;

class PurchaseOrderUpdatedMail extends Mailable
{

     use Queueable, SerializesModels;

    public $order;

    public function __construct(PurchaseOrder $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Purchase Order Updated' . $this->order->purchaseManager->name)
                    ->view('emails.purchase-order-updated');
    }
}
