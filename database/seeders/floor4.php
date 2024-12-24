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
            $unitNumber = 1;
            $slotCountInRow = 0;
            $side = $this->getSide($vaultNumber);

            if (in_array($vaultNumber, [5, 10])) {
                $halfSlots = $totalSlots / 2;
            } else {
                $halfSlots = $totalSlots;
            }

            for ($i = 1; $i <= $totalSlots; $i++) {
                $slotCountInRow++;

                if ($slotCountInRow === 5 || $slotCountInRow === 6) {
                    $price = 40000;
                    $type = 'standard';
                } elseif ($slotCountInRow === 2 || $slotCountInRow === 3 || $slotCountInRow === 4) {
                    $price = 60000;
                    $type = 'superior';
                } else {
                    $price = 50000;
                    $type = 'premium';
                }

                if ($slotCountInRow === 6) {
                    $slotCountInRow = 0;
                }

                if ($vaultNumber == 5 || $vaultNumber == 10) {
                    if ($i > $halfSlots) {
                        $side = 'R';
                    }
                }

                $formattedUnitNumber = str_pad($unitNumber++, 3, '0', STR_PAD_LEFT);
                $levelNumber = $slotCountInRow === 0 ? 6 : $slotCountInRow;
                $unitId = "{$floor}" . chr(64 + $vaultNumber) . "{$levelNumber}-{$formattedUnitNumber}{$side}";

                ColumbarySlot::create([
                    'unit_number' => $formattedUnitNumber,
                    'slot_number' => $slotNumber++,
                    'floor_number' => $floor,
                    'vault_number' => $vaultNumber,
                    'level_number' => $levelNumber,
                    'type' => $type,
                    'price' => $price,
                    'status' => 'Available',
                    'side' => $side,
                    'unit_id' => $unitId
                ]);
            }
        }

        $totalSlots = array_sum($rackSpecs);
        echo "Total slots generated: {$totalSlots}\n";
    }

    private function getSide($vaultNumber)
    {
        $sideMapping = [
            1 => 'L',
            2 => 'L',
            3 => 'L',
            4 => 'L',
            5 => 'L',
            6 => 'R',
            7 => 'R',
            8 => 'L',
            9 => 'L',
            10 => 'L',
            11 => 'R',
            12 => 'R',
            13 => 'L',
            14 => 'L',
            15 => 'L'
        ];

        return $sideMapping[$vaultNumber] ?? 'L';
    }
}
