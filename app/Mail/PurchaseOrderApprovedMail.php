<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\PurchaseOrder;

class PurchaseOrderApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $po;
    public $receiverType;

    //Function for construct
    public function __construct(PurchaseOrder $po, $receiverType) {
        $this->po = $po;
        $this->receiverType = $receiverType;
    }

    //Function for build
    public function build() {
        return $this->subject('Purchase Order Approved - #' . $this->po->id)
            ->view('emails.purchase-order-approved');
    }
}
