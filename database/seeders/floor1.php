<?php

namespace Database\Seeders;

use App\Models\ColumbarySlot;
use Illuminate\Database\Seeder;

class floor1 extends Seeder
{
    public function run()
    {
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
            $unitNumber = 1;
            $slotCountInRow = 0;
            $side = 'L'; // Default side

            if (in_array($vaultNumber, [3, 8])) {
                $halfSlots = $totalSlots / 2;
            } else {
                $halfSlots = $totalSlots;
            }

            for ($i = 1; $i <= $totalSlots; $i++) {
                $slotCountInRow++;

                if ($slotCountInRow === 5 || $slotCountInRow === 6) {
                    $price = 40000;
                    $type = 'Standard';
                } elseif ($slotCountInRow === 2 || $slotCountInRow === 3 || $slotCountInRow === 4) {
                    $price = 60000;
                    $type = 'Superior';
                } else {
                    $price = 50000;
                    $type = 'Premium';
                }

                if ($slotCountInRow === 6) {
                    $slotCountInRow = 0;
                }

                if ($vaultNumber == 3 || $vaultNumber == 8) {
                    if ($i > $halfSlots) {
                        $side = 'R';
                    }
                } else {
                    $side = $this->getSide($vaultNumber);
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
            4 => 'R',
            5 => 'R',
            6 => 'L',
            7 => 'L',
            9 => 'R',
            10 => 'R'
        ];

        return $sideMapping[$vaultNumber] ?? 'L';
    }
}