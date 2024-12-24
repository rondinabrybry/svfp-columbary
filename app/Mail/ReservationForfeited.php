<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ReservationForfeited extends Mailable
{
    public $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        return $this->view('emails.reservation_forfeited')
            ->with(['reservation' => $this->reservation]);
    }
}