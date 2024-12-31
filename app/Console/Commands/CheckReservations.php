<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Jobs\SendReminderEmail;
use App\Jobs\SendForfeitureEmail;
use App\Models\Forfeit;
use App\Models\ColumbarySlot;
use App\Models\Payment;
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
            ->whereDate('purchase_date', '=', Carbon::now('Asia/Manila')->subDays(20))
            ->get();

        foreach ($reminderReservations as $reservation) {
            SendReminderEmail::dispatch($reservation);
            $this->info('Reminder email sent to: ' . $reservation->buyer_email);
            Log::info('Reminder email sent to: ' . $reservation->buyer_email);
        }

        // Send forfeiture emails for reservations not paid after 30 days
        $forfeitureReservations = Reservation::where('payment_status', 'Reserved')
            ->whereDate('purchase_date', '=', Carbon::now('Asia/Manila')->subDays(30))
            ->get();

            foreach ($forfeitureReservations as $reservation) {
                SendForfeitureEmail::dispatch($reservation);
                $this->info('Forfeiture email sent to: ' . $reservation->buyer_email);
                Log::info('Forfeiture email sent to: ' . $reservation->buyer_email);
    
                // Move the data to the forfeits table
                Forfeit::create([
                    'columbary_slot_id' => $reservation->columbary_slot_id,
                    'unit_id' => $reservation->unit_id,
                    'buyer_name' => $reservation->buyer_name,
                    'buyer_email' => $reservation->buyer_email,
                    'payment_status' => 'Forfeit',
                    'price' => $reservation->price,
                    'unit_price' => $reservation->unit_price,
                    'purchase_date' => $reservation->purchase_date,
                    'floor_number' => $reservation->floor_number,
                    'vault_number' => $reservation->vault_number,
                    'level_number' => $reservation->level_number,
                    'type' => $reservation->type,
                ]);
    
                // Update the columbary_slots.status to 'Available' or 'Forfeited'
                $slot = ColumbarySlot::find($reservation->columbary_slot_id);
                if ($slot) {
                    $slot->status = 'Available'; // or 'Forfeited' based on your business logic
                    $slot->save();
                }
    
                // Delete the data from the payments table
                Payment::where('columbary_slot_id', $reservation->columbary_slot_id)->delete();
    
                // Delete the data from the reservations table
                $reservation->delete();
            }

        return 0;
    }
}