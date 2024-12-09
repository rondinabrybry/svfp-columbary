<?php

namespace App\Http\Controllers;

use App\Models\ColumbarySlot;
use App\Models\Payment;
use Illuminate\Http\Request;

class ColumbaryController extends Controller
{
    public function index()
    {
        $floors = ColumbarySlot::select('floor_number')
            ->distinct()
            ->orderBy('floor_number')
            ->get();

        $slots = ColumbarySlot::select('*')
            ->orderBy('floor_number')
            ->orderByRaw('CAST(slot_number AS UNSIGNED)') // Ensures numeric ordering
            ->get()
            ->groupBy('floor_number');

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
            \Log::error('Error fetching slot info: ' . $e->getMessage());

            return response()->json(['error' => 'Failed to load slot information.'], 500);
        }
    }

    public function listSlots()
    {
        $slots = ColumbarySlot::with('payment')
            ->orderBy('floor_number')
            ->orderByRaw('CAST(slot_number AS UNSIGNED)')
            ->get()
            ->groupBy('floor_number');

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

        $validatedData = $request->validate([
            'slot_number' => 'required|string',
            'floor_number' => 'required|integer',
            'price' => 'required|numeric',
            'status' => 'required|in:Available,Reserved,Sold,Not Available'
        ]);

        $slot->update($validatedData);

        return redirect()->route('columbary.list')->with('success', 'Slot updated successfully');
    }

    public function delete($id)
    {
        $slot = ColumbarySlot::findOrFail($id);

        // Optional: Add checks to prevent deletion of sold or reserved slots
        if ($slot->status !== 'Available') {
            return back()->with('error', 'Cannot delete non-available slots');
        }

        $slot->delete();

        return redirect()->route('columbary.list')->with('success', 'Slot deleted successfully');
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
