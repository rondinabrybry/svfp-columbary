<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Models\ColumbarySlot;
use Illuminate\Support\Facades\Mail;

class ForfeitReservations extends Command
{
    protected $signature = 'forfeit:reservations';
    protected $description = 'Forfeit reservations that have expired';

    public function handle()
    {
        $reservations = Reservation::where('expiration_date', '<=', now())->get();

        foreach ($reservations as $reservation) {
            $slot = ColumbarySlot::find($reservation->columbary_slot_id);
            $slot->status = 'Available';
            $slot->save();

            Mail::to($reservation->buyer_email)->send(new ReservationForfeited($reservation));

            $reservation->delete();
        }

        $this->info('Expired reservations forfeited successfully.');
    }
}