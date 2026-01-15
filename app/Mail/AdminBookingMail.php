<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminBookingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $patient;

    public function __construct($patient)
    {
        $this->patient = $patient;
    }

    public function build()
    {
        return $this->subject('New Patient Booking Received')
            ->view('emails.admin-booking');
    }
}
