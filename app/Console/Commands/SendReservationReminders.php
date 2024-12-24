<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;

class SendReservationReminders extends Command
{
    protected $signature = 'send:reservation-reminders';
    protected $description = 'Send reminders for reservations nearing expiration';

    public function handle()
    {
        $reservations = Reservation::where('expiration_date', '<=', now()->addDays(10))
            ->where('expiration_date', '>', now())
            ->get();

        foreach ($reservations as $reservation) {
            Mail::to($reservation->buyer_email)->send(new ReservationReminder($reservation));
        }

        $this->info('Reminders sent successfully.');
    }
}