<?php

namespace Database\Seeders;

use App\Models\ColumbarySlot;
use Illuminate\Database\Seeder;

class floor5_add extends Seeder
{
    public function run()
    {
        
        $floor = 5;

        
        $rackSpecs = [
            1 => 10,
            2 => 12,
            
        ];

        
        $maxSlotNumber = ColumbarySlot::where('floor_number', $floor)->max('slot_number');
        $slotNumber = $maxSlotNumber ? $maxSlotNumber + 1 : 1;

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

                
                $formattedSlotNumber = str_pad($slotNumber++, 5, '0', STR_PAD_LEFT);

                ColumbarySlot::create([
                    'slot_number' => $formattedSlotNumber,
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