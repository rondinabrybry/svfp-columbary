<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ReservationReminder extends Mailable
{
    public $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        return $this->view('emails.reservation_reminder')
            ->with(['reservation' => $this->reservation]);
    }
}