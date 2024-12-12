<?php

namespace App\Http\Controllers;

use App\Models\ColumbarySlot;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColumbaryController extends Controller
{
    public function index()
    {
        // Retrieve all unique floor numbers
        $floors = ColumbarySlot::distinct('floor_number')->pluck('floor_number');
        
        // Retrieve all slots ordered by floor_number, vault_number, and slot_number
        $slots = ColumbarySlot::orderBy('floor_number')
            ->orderBy('vault_number')
            ->orderByRaw('CAST(slot_number AS UNSIGNED)')
            ->get()
            ->groupBy(['floor_number', 'vault_number']);
    
        return view('columbary.index', compact('floors', 'slots'));
    }
    
    


    public function reserveSlot(Request $request, $id)
    {
        $slot = ColumbarySlot::findOrFail($id);

        if ($slot->status !== 'Available') {
            return back()->with('error', 'Slot is not available.');
        }

        $slot->status = 'Reserved';
        $slot->save();

        Payment::create([
            'columbary_slot_id' => $slot->id,
            'buyer_name' => $request->buyer_name,
            'contact_info' => $request->contact_info,
            'payment_status' => 'Reserved',
        ]);

        return redirect()->route('columbary.index')->with('success', 'Slot reserved successfully.');
    }

    public function markAsPaid($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->payment_status = 'Paid';
        $payment->save();

        $payment->columbarySlot->update(['status' => 'Sold']);

        return back()->with('success', 'Payment marked as paid.');
    }

    public function getSlotInfo($slotId)
    {
        try {
            $slot = ColumbarySlot::with('payment')->findOrFail($slotId);
            $payment = $slot->payment;

            if (!$payment) {
                return response()->json(['error' => 'No payment information found'], 404);
            }

            // Find all slots reserved by the same buyer
            $reservedSlots = ColumbarySlot::whereHas('payment', function ($query) use ($payment) {
                $query->where('buyer_name', $payment->buyer_name);
            })->pluck('slot_number');

            return response()->json([
                'id' => $payment->id,
                'buyer_name' => $payment->buyer_name,
                'contact_info' => $payment->contact_info,
                'reserved_slots' => $reservedSlots,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load slot information.'], 500);
        }
    }

    public function listSlots()
    {
        // Retrieve slots grouped by floor and vault
        $slots = ColumbarySlot::with('payment')
            ->orderBy('floor_number')
            ->orderBy('vault_number')
            ->orderByRaw('CAST(slot_number AS UNSIGNED)')
            ->get()
            ->groupBy(['floor_number', 'vault_number']);

        return view('columbary.list-slots', compact('slots'));
    }

    public function edit($id)
    {
        $slot = ColumbarySlot::with('payment')->findOrFail($id);
        return view('columbary.edit-slot', compact('slot'));
    }

    public function update(Request $request, $id)
    {
        $slot = ColumbarySlot::findOrFail($id);

        // Validate slot data
        $validatedSlotData = $request->validate([
            'slot_number' => 'required|string',
            'vault_number' => 'required|integer',
            'floor_number' => 'required|integer',
            'price' => 'required|numeric',
            'status' => 'required|in:Available,Reserved,Sold,Not Available'
        ]);

        // Validate payment data (only if payment exists or status is not Available)
        $validatedPaymentData = [];
        if ($slot->payment || $validatedSlotData['status'] !== 'Available') {
            $validatedPaymentData = $request->validate([
                'buyer_name' => 'sometimes|nullable|string|max:255',
                'contact_info' => 'sometimes|nullable|string|max:255',
                'payment_status' => 'sometimes|nullable|in:Reserved,Paid,Pending,Cancelled'
            ]);
        }

        // Start a database transaction
        DB::beginTransaction();
        try {
            // Update slot information
            $slot->update($validatedSlotData);

            // Update or create payment information if applicable
            if (!empty($validatedPaymentData)) {
                if ($slot->payment) {
                    // Update existing payment
                    $slot->payment->update($validatedPaymentData);
                } elseif ($validatedSlotData['status'] !== 'Available') {
                    // Create new payment if slot is not available
                    Payment::create([
                        'columbary_slot_id' => $slot->id,
                        'buyer_name' => $validatedPaymentData['buyer_name'] ?? null,
                        'contact_info' => $validatedPaymentData['contact_info'] ?? null,
                        'payment_status' => $validatedPaymentData['payment_status'] ?? 'Pending'
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('columbary.list')->with('success', 'Slot and payment information updated successfully');
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            return back()->with('error', 'Failed to update slot information: ' . $e->getMessage());
        }
    }

    public function markNotAvailable($id)
    {
        $slot = ColumbarySlot::findOrFail($id);
    
        // Prevent marking non-available slots
        if ($slot->status !== 'Available') {
            return back()->with('error', 'Only available slots can be marked as Not Available');
        }
    
        $slot->status = 'Not Available';
        $slot->save();
    
        return redirect()->route('columbary.list')->with('success', 'Slot marked as Not Available');
    }

    public function createSlots(Request $request)
    {
        $validatedData = $request->validate([
            'floor_number' => 'required|integer|min:1|max:4',
            'number_of_slots' => 'required|integer|min:1|max:20',
            'price' => 'required|numeric|min:0'
        ]);
    
        // Find the highest slot number across all slots
        $lastSlot = ColumbarySlot::orderByRaw('CAST(slot_number AS UNSIGNED) DESC')->first();
        $startingSlotNumber = $lastSlot ? (int)$lastSlot->slot_number + 1 : 1;
    
        // Create new slots
        $newSlots = [];
        for ($i = 0; $i < $validatedData['number_of_slots']; $i++) {
            $newSlots[] = ColumbarySlot::create([
                'slot_number' => (string)($startingSlotNumber + $i),
                'floor_number' => $validatedData['floor_number'],
                'status' => 'Available',
                'price' => $validatedData['price']
            ]);
        }
    
        return redirect()->route('columbary.list')->with('success', 'Slots added successfully');
    }
}
