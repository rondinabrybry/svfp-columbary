<?php
namespace App\Http\Controllers;

use App\Models\ColumbarySlot;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColumbarySlotController extends Controller
{
    public function index()
    {
        $slots = ColumbarySlot::select(['id', 'slot_number', 'floor_number', 'vault_number', 'status', 'price', 'type', 'level_number'])
            ->with(['payment:id,columbary_slot_id,buyer_name,payment_status'])
            ->orderBy('floor_number')
            ->orderBy('vault_number')
            ->orderByRaw('CAST(slot_number AS UNSIGNED)')
            ->paginate(10)
            ->groupBy(['floor_number', 'vault_number']);

        $floors = $slots->keys();

        return view('columbary.index', compact('floors', 'slots'));
    }

    public function Loadindex()
    {
        $slots = ColumbarySlot::select(['id', 'slot_number', 'floor_number', 'vault_number', 'status', 'price', 'type', 'level_number'])
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
            ->paginate(10)
            ->groupBy(['floor_number', 'vault_number']);

        return view('columbary.list-slots', compact('slots'));
    }

    public function loadAllSlots()
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
        $request->validate([
            'floor' => 'required|integer',
            'rackSpecs' => 'required|string',
        ]);

        $floor = $request->input('floor');
        $rackSpecsInput = $request->input('rackSpecs');
        $rackSpecs = [];

        foreach (explode(',', $rackSpecsInput) as $spec) {
            list($vaultNumber, $totalSlots) = explode(':', $spec);
            $rackSpecs[(int)$vaultNumber] = (int)$totalSlots;
        }

        $lastSlotNumber = ColumbarySlot::max('slot_number') ?: 0;
        $slotNumber = $lastSlotNumber + 1;

        foreach ($rackSpecs as $vaultNumber => $totalSlots) {
            $slotCountInRow = 0;

            for ($i = 1; $i <= $totalSlots; $i++) {
                $slotCountInRow++;

                if ($slotCountInRow === 5 || $slotCountInRow === 6) {
                    $price = 40000;
                    $type = 'standard';
                } elseif ($slotCountInRow === 2 || $slotCountInRow === 3 || $slotCountInRow === 4) {
                    $price = 60000;
                    $type = 'premium_plus';
                } else {
                    $price = 50000;
                    $type = 'premium';
                }

                // Reset slotCountInRow after reaching 6
                if ($slotCountInRow === 6) {
                    $slotCountInRow = 0;
                }
                ColumbarySlot::create([
                    'slot_number' => $slotNumber++,
                    'floor_number' => $floor,
                    'vault_number' => $vaultNumber,
                    'level_number' => $slotCountInRow === 0 ? 6 : $slotCountInRow,
                    'type' => $type,
                    'price' => $price,
                    'status' => 'Available'
                ]);
            }
        }

        return redirect()->route('columbary.list')->with('success', 'Slots added successfully.');
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
            return redirect()->route('columbary.loadAll')->with('success', 'Slot updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return view('columbary.loadAll')->with('error', 'Failed to update slot: ' . $e->getMessage());
        }
    }

    public function makeAvailable($id)
    {
        $slot = ColumbarySlot::findOrFail($id);
    
        // Delete the related payment records
        Payment::where('columbary_slot_id', $slot->id)->delete();
    
        // Update the slot status to 'Available'
        $slot->update(['status' => 'Available']);
    
        return redirect()->back()->with('success', 'Slot is now available and related payments have been deleted.');
    }

    public function markNotAvailable($id)
    {
        $slot = ColumbarySlot::findOrFail($id);

        if ($slot->status !== 'Available') {
            return back()->with('error', 'Only available slots can be marked as Not Available');
        }

        $slot->update(['status' => 'Not Available']);
        return redirect()->back()->with('success', 'Slot marked as Not Available');
    }
}
