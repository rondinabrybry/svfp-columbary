<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Jobs\SendReminderEmail;
use Carbon\Carbon;

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
        $reservations = Reservation::where('payment_status', 'Reserved')
            ->whereDate('purchase_date', '=', Carbon::now('Asia/Manila'))
            ->get();

        foreach ($reservations as $reservation) {
            SendReminderEmail::dispatch($reservation);
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