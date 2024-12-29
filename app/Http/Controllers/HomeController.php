<?php

namespace App\Http\Controllers;

use App\Models\ColumbarySlot;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Jobs\SendReservationEmail;
use App\Mail\ReservationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function showSlots()
    {
        $slots = ColumbarySlot::select(['id', 'unit_number', 'floor_number', 'vault_number', 'slot_number', 'level_number', 'status', 'side', 'type', 'unit_id', 'price'])
            ->orderBy('floor_number')
            ->orderBy('vault_number')
            ->orderBy('slot_number')
            ->get()
            ->groupBy('floor_number')
            ->map(function ($floorSlots) {
                return $floorSlots->groupBy('vault_number');
            });

        return view('home', compact('slots'));
    }

    public function getSlotDetails($slotId)
    {
        $slot = ColumbarySlot::with('payment')->findOrFail($slotId);

        if ($slot->status === 'Reserved' || $slot->status === 'Sold') {
            $payment = $slot->payment;

            return response()->json([
                'slotNumber' => $slot->slot_number,
                'floor' => $slot->floor_number,
                'vault' => $slot->vault_number,
                'unit' => $slot->slot_number,
                'level' => $slot->level_number,
                'type' => $slot->type,
                'status' => $slot->status,
                'unitId' => $slot->unit_id,
                'side' => $slot->side,
                'price' => $slot->price,
                'buyerName' => $payment->buyer_name ?? null,
                'buyerAddress' => $payment->buyer_address ?? null,
                'buyerEmail' => $payment->buyer_email ?? null,
                'contactInfo' => $payment->contact_info ?? null,
                'paymentStatus' => $payment->payment_status ?? null,
                'paymentDate' => $payment->created_at ? $payment->created_at->format('M d, Y h:i A') : 'N/A'
            ]);
        }

        return response()->json([
            'slotNumber' => $slot->slot_number,
            'floor' => $slot->floor_number,
            'vault' => $slot->vault_number,
            'unit' => $slot->slot_number,
            'level' => $slot->level_number,
            'type' => $slot->type,
            'status' => $slot->status,
            'unitId' => $slot->unit_id,
            'side' => $slot->side,
            'price' => $slot->price
        ]);
    }


    public function reserveSlot(Request $request)
    {
        try {
            $request->validate([
                'slot_id' => 'required|exists:columbary_slots,id',
                'buyer_name' => 'required|string|max:255',
                'buyer_address' => 'required|string|max:255',
                'buyer_email' => 'required|email|max:255',
                'contact_info' => 'required|string|max:255',
                'price' => 'required|numeric',
                'reservation_type' => 'required|string|in:reserve,full',
            ]);
    
            $slot = ColumbarySlot::findOrFail($request->slot_id);
    
            if ($slot->status === 'Reserved' || $slot->status === 'Sold') {
                return response()->json(['message' => 'Slot is already reserved or sold.'], 400);
            }
    
            $slot->status = $request->reservation_type === 'full' ? 'Sold' : 'Reserved';
            $slot->save();
    
            $paymentStatus = $request->reservation_type === 'full' ? 'Paid' : 'Reserved';
    
            Payment::create([
                'columbary_slot_id' => $slot->id,
                'buyer_name' => $request->buyer_name,
                'buyer_address' => $request->buyer_address,
                'buyer_email' => $request->buyer_email,
                'contact_info' => $request->contact_info,
                'payment_status' => $paymentStatus,
                'price' => $request->price,
            ]);
    
            $emailStatus = 'Email not sent';
    
            if ($slot->status === 'Reserved') {
                $reservation = Reservation::create([
                    'columbary_slot_id' => $slot->id,
                    'unit_id' => $slot->unit_id,
                    'buyer_name' => $request->buyer_name,
                    'buyer_email' => $request->buyer_email,
                    'payment_status' => 'Reserved',
                    'price' => $request->price,
                    'unit_price' => $slot->price, // Assuming unit_price is the same as slot price
                    'purchase_date' => Carbon::now('Asia/Manila'),
                    'floor_number' => $slot->floor_number,
                    'vault_number' => $slot->vault_number,
                    'level_number' => $slot->level_number,
                    'type' => $slot->type,
                ]);
    
                try {
                    // Dispatch the job to send the reservation email
                    SendReservationEmail::dispatch($reservation);
                    $emailStatus = 'Email sent successfully';
                } catch (\Exception $e) {
                    Log::error('Error sending reservation email: ' . $e->getMessage());
                    $emailStatus = 'Failed to send email';
                }
            }
    
            return response()->json(['message' => 'Slot reserved successfully!', 'email_status' => $emailStatus]);
        } catch (\Exception $e) {
            Log::error('Error reserving slot: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while reserving the slot.'], 500);
        }
    }
}