<?php

namespace Database\Seeders;

use App\Models\ColumbarySlot;
use Illuminate\Database\Seeder;

class floor_1_Rack_1 extends Seeder
{
    public function run()
    {
        // Rack specifications
        $rackSpecs = [
            1 => 16, // Rack 1: 16 slots
            2 => 12, // Rack 2: 12 slots
            3 => 60, // Rack 3: 60 slots
            4 => 32, // Rack 4: 32 slots
            5 => 24, // Rack 5: 24 slots
            6 => 60  // Rack 6: 60 slots
        ];

        $slotNumber = 1;
        $floor = 1;

        foreach ($rackSpecs as $vaultNumber => $totalSlots) {
            $slotCountInRow = 0; // Track slots per row

            for ($i = 1; $i <= $totalSlots; $i++) {
                $slotCountInRow++;

                // Determine the price based on the slot position in the row
                if ($slotCountInRow === 3 || $slotCountInRow === 4) {
                    $price = 20000; // Price for 3rd and 4th slots in a row
                } else {
                    $price = 10000; // Price for other slots
                }

                // Reset slot count after every 6 slots (1 row)
                if ($slotCountInRow === 6) {
                    $slotCountInRow = 0;
                }

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
