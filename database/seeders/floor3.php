<?php

namespace Database\Seeders;

use App\Models\ColumbarySlot;
use Illuminate\Database\Seeder;

class floor3 extends Seeder
{
    public function run()
    {
        // Rack specifications
        $rackSpecs = [
            1 => 84,
            2 => 144,
            3 => 168,
            4 => 360,
            5 => 336,
            6 => 360,
            7 => 168,
            8 => 168,
            9 => 360,
            10 => 336,
            11 => 360,
            12 => 168,
            13 => 84,
            14 => 168,
            15 => 84
        ];

        // Get the last used slot number to continue from it
        $lastSlotNumber = ColumbarySlot::max('slot_number') ?: 0;
        $slotNumber = $lastSlotNumber + 1; // Start from the next available slot number
        $floor = 3;

        foreach ($rackSpecs as $vaultNumber => $totalSlots) {
            $slotCountInRow = 0; // Track slots per row

            for ($i = 1; $i <= $totalSlots; $i++) {
                $slotCountInRow++;

                // Determine the price based on the slot position in the row
                if ($slotCountInRow === 5 || $slotCountInRow === 6) {
                    $price = 40000;
                } elseif ($slotCountInRow === 2 || $slotCountInRow === 3 || $slotCountInRow === 4) {
                    $price = 60000;
                } else {
                    $price = 50000;
                }

                // Reset slot count after every 6 slots (1 row)
                if ($slotCountInRow === 6) {
                    $slotCountInRow = 0;
                }

                // Create the ColumbarySlot entry
                ColumbarySlot::create([
                    'slot_number' => $slotNumber++,
                    'floor_number' => $floor,
                    'vault_number' => $vaultNumber,
                    'price' => $price,
                    'status' => 'Available'
                ]);
            }
        }

        // Verify total number of slots
        $totalSlots = array_sum($rackSpecs);
        echo "Total slots generated: {$totalSlots}\n";
    }
}
