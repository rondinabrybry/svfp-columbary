<?php

namespace Database\Seeders;

use App\Models\ColumbarySlot;
use Illuminate\Database\Seeder;

class floor4 extends Seeder
{
    public function run()
    {
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

        $lastSlotNumber = ColumbarySlot::max('slot_number') ?: 0;
        $slotNumber = $lastSlotNumber + 1;
        $floor = 4;

        foreach ($rackSpecs as $vaultNumber => $totalSlots) {
            $slotCountInRow = 0;

            for ($i = 1; $i <= $totalSlots; $i++) {
                $slotCountInRow++;

                
                if ($slotCountInRow === 5 || $slotCountInRow === 6) {
                    $price = 40000;
                } elseif ($slotCountInRow === 2 || $slotCountInRow === 3 || $slotCountInRow === 4) {
                    $price = 60000;
                } else {
                    $price = 50000;
                }

                
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

        
        $totalSlots = array_sum($rackSpecs);
        echo "Total slots generated: {$totalSlots}\n";
    }
}
