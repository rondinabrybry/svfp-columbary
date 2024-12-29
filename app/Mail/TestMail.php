<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->view('emails.test')
                    ->subject('Test Email')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
    }
}