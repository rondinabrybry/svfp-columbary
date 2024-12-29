<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Jobs\SendReminderEmail;
use App\Jobs\SendForfeitureEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckReservations extends Command
{
    protected $signature = 'reservations:check';
    protected $description = 'Check reservations and send reminder emails if there are only 10 days left to pay';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('Running reservations:check command');

        // Send reminder emails for reservations with 10 days left to pay
        $reminderReservations = Reservation::where('payment_status', 'Reserved')
            ->whereDate('purchase_date', '=', Carbon::now('Asia/Manila')->subDays(1))
            ->get();

        foreach ($reminderReservations as $reservation) {
            SendReminderEmail::dispatch($reservation);
            $this->info('Reminder email sent to: ' . $reservation->buyer_email);
            Log::info('Reminder email sent to: ' . $reservation->buyer_email);
        }

        // Send forfeiture emails for reservations not paid after 30 days
        $forfeitureReservations = Reservation::where('payment_status', 'Reserved')
            ->whereDate('purchase_date', '=', Carbon::now('Asia/Manila')->subDays(2))
            ->get();

        foreach ($forfeitureReservations as $reservation) {
            SendForfeitureEmail::dispatch($reservation);
            $this->info('Forfeiture email sent to: ' . $reservation->buyer_email);
            Log::info('Forfeiture email sent to: ' . $reservation->buyer_email);
        }

        return 0;
    }
}



// namespace App\Console\Commands;

// use Illuminate\Console\Command;
// use App\Models\Reservation;
// use App\Jobs\SendReminderEmail;
// use Carbon\Carbon;

// class CheckReservations extends Command
// {
//     protected $signature = 'reservations:check';
//     protected $description = 'Check reservations and send reminder emails if there are only 10 days left to pay';

//     public function __construct()
//     {
//         parent::__construct();
//     }

//     public function handle()
//     {
//         // For testing purposes, check reservations made today
//         $reservations = Reservation::where('payment_status', 'Reserved')
//             ->whereDate('purchase_date', '=', Carbon::now('Asia/Manila'))
//             ->get();

//         foreach ($reservations as $reservation) {
//             SendReminderEmail::dispatch($reservation);
//             $this->info('Reminder email sent to: ' . $reservation->buyer_email);
//         }

//         return 0;
//     }
// }