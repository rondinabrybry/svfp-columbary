<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class ForfeitureMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        $columbarySlot = $this->reservation->columbarySlot;

        return $this->view('emails.forfeiture')
                    ->subject('Reservation Forfeited')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->with([
                        'buyer_name' => $this->reservation->buyer_name,
                        'slot_number' => $columbarySlot->slot_number,
                        'unit_id' => $this->reservation->unit_id,
                        'floor_number' => $columbarySlot->floor_number,
                        'vault_number' => $columbarySlot->vault_number,
                        'level_number' => $columbarySlot->level_number,
                        'type' => $columbarySlot->type,
                        'price' => $this->reservation->price,
                        'unit_price' => $this->reservation->unit_price,
                        'purchase_date' => Carbon::parse($this->reservation->purchase_date)->format('M d, Y'),
                    ]);
    }
}