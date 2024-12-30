<?php

namespace App\Http\Controllers;

use App\Models\ColumbarySlot;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $floors = ColumbarySlot::distinct()->pluck('floor_number');
        $data = [];

        foreach ($floors as $floor) {
            $racks = ColumbarySlot::where('floor_number', $floor)->distinct()->pluck('vault_number');
            $data[$floor] = [];

            foreach ($racks as $rack) {
                $levels = ColumbarySlot::where('floor_number', $floor)
                    ->where('vault_number', $rack)
                    ->distinct()
                    ->get(['level_number', 'status']);

                $data[$floor][$rack] = $levels->unique('level_number')->map(function ($level) {
                    return [
                        'level_number' => $level->level_number,
                        'checked' => $level->status == 'Not Available'
                    ];
                });
            }
        }

        return view('settings', compact('data'));
    }

    public function update(Request $request)
    {
        $selectedLevels = $request->input('levels', []);

        // Update selected levels to "Not Available"
        foreach ($selectedLevels as $level) {
            list($floor, $rack, $levelNumber) = explode('_', $level);

            ColumbarySlot::where('floor_number', $floor)
                ->where('vault_number', $rack)
                ->where('level_number', $levelNumber)
                ->where('status', 'Available')
                ->update(['status' => 'Not Available']);
        }

        // Update unselected levels to "Available"
        $allLevels = ColumbarySlot::whereIn('status', ['Available', 'Not Available'])->get();
        foreach ($allLevels as $slot) {
            $levelKey = "{$slot->floor_number}_{$slot->vault_number}_{$slot->level_number}";
            if (!in_array($levelKey, $selectedLevels) && $slot->status == 'Not Available') {
                $slot->update(['status' => 'Available']);
            }
        }

        return redirect()->route('settings')->with('success', 'Settings updated successfully.');
    }
}