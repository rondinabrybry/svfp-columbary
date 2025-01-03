<?php

namespace App\Http\Controllers;

use App\Models\ColumbarySlot;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColumbaryController extends Controller

{public function index()
    {
        $slots = ColumbarySlot::select(['id', 'slot_number', 'floor_number', 'vault_number', 'status', 'price'])
            ->with(['payment:id,columbary_slot_id,buyer_name,payment_status'])
            ->orderBy('floor_number')
            ->orderBy('vault_number')
            ->orderByRaw('CAST(slot_number AS UNSIGNED)')
            ->get()
            ->groupBy(['floor_number', 'vault_number']);
    
        $floors = $slots->keys();
    
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
    
            // Find all slots reserved by the same buyer with payment_status 'Reserved'
            $reservedSlots = ColumbarySlot::whereHas('payment', function ($query) use ($payment) {
                $query->where('buyer_name', $payment->buyer_name)
                      ->where('payment_status', 'Reserved');
            })->pluck('slot_number');
    
            // Find all slots owned by the same buyer with payment_status 'Paid'
            $ownedSlots = ColumbarySlot::whereHas('payment', function ($query) use ($payment) {
                $query->where('buyer_name', $payment->buyer_name)
                      ->where('payment_status', 'Paid');
            })->pluck('slot_number');
    
            return response()->json([
                'id' => $payment->id,
                'buyer_name' => $payment->buyer_name,
                'buyer_address' => $payment->buyer_address,
                'buyer_email' => $payment->buyer_email,
                'contact_info' => $payment->contact_info,
                'reserved_slots' => $reservedSlots,
                'owned_slots' => $ownedSlots,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load slot information.'], 500);
        }
    }

    public function listSlots()
    {
        $slots = ColumbarySlot::with('payment')
            ->orderBy('floor_number')
            ->orderBy('vault_number')
            ->orderByRaw('CAST(slot_number AS UNSIGNED)')
            ->get()
            ->groupBy(['floor_number', 'vault_number']);

        return view('columbary.list-slots', compact('slots'));
    }

    public function getVaults($floor)
{
    $vaults = ColumbarySlot::select(['id', 'slot_number', 'vault_number', 'status', 'price'])
        ->where('floor_number', $floor)
        ->with(['payment:id,columbary_slot_id,buyer_name,payment_status'])
        ->orderBy('vault_number')
        ->orderByRaw('CAST(slot_number AS UNSIGNED)')
        ->get()
        ->groupBy('vault_number');

    $html = view('columbary.partials.vaults', compact('vaults'))->render();

    return response()->json(['html' => $html]);
}


    public function edit($id)
    {
        $slot = ColumbarySlot::with('payment')->findOrFail($id);
        return view('columbary.edit-slot', compact('slot'));
    }
    public function makeAvailable($id)
    {
        $slot = ColumbarySlot::findOrFail($id);
    
        DB::beginTransaction();
        try {
            $slot->update(['status' => 'Available']);
    
            if ($slot->payment) {
                $slot->payment->delete();
            }
    
            DB::commit();
            return redirect()->route('columbary.list')->with('success', 'Slot is now available.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to remove reservation: ' . $e->getMessage());
        }
    }
    
    public function update(Request $request, $id)
    {
        $slot = ColumbarySlot::findOrFail($id);

        $validatedSlotData = $request->validate([
            'slot_number' => 'required|string',
            'vault_number' => 'required|integer',
            'floor_number' => 'required|integer',
            'price' => 'required|numeric',
            'status' => 'required|in:Available,Reserved,Sold,Not Available'
        ]);

        $validatedPaymentData = [];
        if ($slot->payment || $validatedSlotData['status'] !== 'Available') {
            $validatedPaymentData = $request->validate([
                'buyer_name' => 'sometimes|nullable|string|max:255',
                'contact_info' => 'sometimes|nullable|string|max:255',
                'payment_status' => 'sometimes|nullable|in:Reserved,Paid,Pending,Cancelled'
            ]);
        }

        DB::beginTransaction();
        try {
            $slot->update($validatedSlotData);

            if (!empty($validatedPaymentData)) {
                if ($slot->payment) {
                    
                    $slot->payment->update($validatedPaymentData);
                } elseif ($validatedSlotData['status'] !== 'Available') {
                    
                    Payment::create([
                        'columbary_slot_id' => $slot->id,
                        'buyer_name' => $validatedPaymentData['buyer_name'] ?? null,
                        'contact_info' => $validatedPaymentData['contact_info'] ?? null,
                        'payment_status' => $validatedPaymentData['payment_status'] ?? 'Pending'
                    ]);
                }
            }

            
            DB::commit();

            return redirect()->route('columbary.list')->with('success', 'Slot and payment information updated successfully');
        } catch (\Exception $e) {
            
            DB::rollBack();

            return back()->with('error', 'Failed to update slot information: ' . $e->getMessage());
        }
    }

    public function markNotAvailable($id)
    {
        $slot = ColumbarySlot::findOrFail($id);
    
        
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
    
        
        $lastSlot = ColumbarySlot::orderByRaw('CAST(slot_number AS UNSIGNED) DESC')->first();
        $startingSlotNumber = $lastSlot ? (int)$lastSlot->slot_number + 1 : 1;
    
        
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
