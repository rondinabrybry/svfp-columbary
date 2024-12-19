<?php
namespace App\Http\Controllers;

use App\Models\ColumbarySlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColumbarySlotController extends Controller
{
    public function index()
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

    public function edit($id)
    {
        $slot = ColumbarySlot::with('payment')->findOrFail($id);
        return view('columbary.edit-slot', compact('slot'));
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

        DB::beginTransaction();
        try {
            $slot->update($validatedSlotData);

            DB::commit();
            return redirect()->route('columbary.list')->with('success', 'Slot updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update slot: ' . $e->getMessage());
        }
    }

    public function makeAvailable($id)
    {
        $slot = ColumbarySlot::findOrFail($id);
        $slot->update(['status' => 'Available']);
        return redirect()->route('columbary.list')->with('success', 'Slot is now available');
    }

    public function markNotAvailable($id)
    {
        $slot = ColumbarySlot::findOrFail($id);

        if ($slot->status !== 'Available') {
            return back()->with('error', 'Only available slots can be marked as Not Available');
        }

        $slot->update(['status' => 'Not Available']);
        return redirect()->route('columbary.list')->with('success', 'Slot marked as Not Available');
    }
}
