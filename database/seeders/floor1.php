<?php

namespace Database\Seeders;

use App\Models\ColumbarySlot;
use Illuminate\Database\Seeder;

class floor1 extends Seeder
{
    public function run()
    {
        // Rack specifications
        $rackSpecs = [
            1 => 168,
            2 => 360,
            3 => 336,
            4 => 360,
            5 => 168,
            6 => 96,
            7 => 192,
            8 => 192,
            9 => 192,
            10 => 96
        ];

        $slotNumber = 1;
        $floor = 1;

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
