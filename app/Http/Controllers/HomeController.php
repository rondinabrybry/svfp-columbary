<?php

namespace App\Http\Controllers;

use App\Models\ColumbarySlot;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function showSlots()
    {
        $slots = ColumbarySlot::select(['id', 'floor_number', 'vault_number', 'slot_number', 'status'])
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
        
        // Check if the slot is reserved or sold
        if ($slot->status === 'Reserved' || $slot->status === 'Sold') {
            $payment = $slot->payment;
            
            return response()->json([
                'slotNumber' => $slot->slot_number,
                'floor' => $slot->floor_number,
                'vault' => $slot->vault_number,
                'status' => $slot->status,
                'buyerName' => $payment->buyer_name ?? null,
                'contactInfo' => $payment->contact_info ?? null,
                'paymentStatus' => $payment->payment_status ?? null,
                // Format the payment date as 'M d, Y h:i A' (e.g., 'Dec 14, 2024 7:16 PM')
                'paymentDate' => $payment->created_at ? $payment->created_at->format('M d, Y h:i A') : 'N/A'
            ]);
        }
        
        return response()->json(['message' => 'No details available'], 404);
    }
    

    public function reserveSlot(Request $request)
    {
        $request->validate([
            'slot_id' => 'required|exists:columbary_slots,id',
            'buyer_name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
        ]);

        $slot = ColumbarySlot::findOrFail($request->slot_id);

        // Update the slot's status
        $slot->status = 'Reserved';
        $slot->save();

        // Add a payment record
        Payment::create([
            'columbary_slot_id' => $slot->id,
            'buyer_name' => $request->buyer_name,
            'contact_info' => $request->contact_info,
            'payment_status' => 'Reserved',
        ]);

        return response()->json(['message' => 'Slot reserved successfully!']);
    }
}