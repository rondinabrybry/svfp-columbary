<?php

namespace App\Http\Controllers;

use App\Models\ColumbarySlot;
use App\Models\Payment;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function showSlots()
    {
        // Group slots by floor and vault
        $slots = ColumbarySlot::all()
            ->groupBy('floor_number')
            ->map(function ($floorSlots) {
                return $floorSlots->groupBy('vault_number')
                    ->map(function ($vaultSlots) {
                        return $vaultSlots->sortBy('slot_number');
                    });
            });

        return view('home', compact('slots'));
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