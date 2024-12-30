<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Payment;

class PaymentCompleteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function build()
    {
        $columbarySlot = $this->payment->columbarySlot;

        return $this->view('emails.payment_complete')
                    ->subject('Payment Complete - Ownership Confirmation')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->with([
                        'buyer_name' => $this->payment->buyer_name,
                        'slot_number' => $columbarySlot->slot_number,
                        'unit_id' => $columbarySlot->unit_id,
                        'price' => $this->payment->price,
                    ]);
    }
}