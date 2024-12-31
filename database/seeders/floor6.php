<?php

namespace Database\Seeders;

use App\Models\ColumbarySlot;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class floor6 extends Seeder
{
    public function run()
    {
        $maxVaultNumber = 17;
        $maxLevelNumber = 6;
        $floorNumber = 6;
        $unitNumber = 1;
        $totalSlots = 0;

        $lastSlotNumber = ColumbarySlot::max('slot_number') ?: 0;
        $slotNumber = $lastSlotNumber + 1; 

        $levelPrices = [
            1 => 50000,
            2 => 60000,
            3 => 60000,
            4 => 60000,
            5 => 40000,
            6 => 40000,
        ];

        $levelTypes = [
            1 => 'Premium',
            2 => 'Superior',
            3 => 'Superior',
            4 => 'Superior',
            5 => 'Standard',
            6 => 'Standard',
        ];

        $vaultSides = [
            1 => 'L', //1
            2 => 'L', //2
            3 => 'L', //3
            4 => 'L', //4
            5 => 'L', //5
            6 => 'R', //5
            7 => 'R', //6
            8 => 'R', //7
            9 => 'L', //8
            10 => 'L', //9
            11 => 'L', //10
            12 => 'R', //10
            13 => 'R', //11
            14 => 'R', //12
            15 => 'L', //13
            16 => 'L', //14
            17 => 'L', //15
        ];

        $vaultCounts = [
            1 => 84, //1
            2 => 144, //2
            3 => 168, //3
            4 => 360, //4
            5 => 168, //5
            6 => 168, //5
            7 => 360, //6
            8 => 168, //7
            9 => 168, //8
            10 => 360, //9
            11 => 168, // 10
            12 => 168, // 10
            13 => 360, //11
            14 => 168, //12
            15 => 84, //13
            16 => 168, //14
            17 => 84 //15
        ];

        $slotsPerLevel = [
            1 => 14, //1
            2 => 24, //2
            3 => 28, //3
            4 => 60, //4
            5 => 28, //5
            6 => 28,//5
            7 => 60,//6
            8 => 28,//7
            9 => 28,//8
            10 => 60,//9
            11 => 28,//10
            12 => 28,//10
            13 => 60,//11
            14 => 28,//12
            15 => 14,//13
            16 => 28,//14
            17 => 14,//15
        ];

        foreach ($vaultCounts as $vaultNumber => $vaultCount) {
            if ($vaultNumber > $maxVaultNumber) {
                break;
            }
            // Reset unit_number for each vault
            $unitNumber = 1;

            for ($i = 0; $i < $vaultCount; $i++) {
                $slotsPerLevelForVault = $slotsPerLevel[$vaultNumber];
                $levelNumber = (int)($i / $slotsPerLevelForVault) + 1;
                if ($levelNumber > $maxLevelNumber) {
                    $levelNumber = $maxLevelNumber;
                }
            
                $side = $vaultSides[$vaultNumber];
            
                $slotNumberPadded = str_pad($unitNumber, 3, '0', STR_PAD_LEFT);

                $adjustedVaultNumber = $vaultNumber;
                if ($vaultNumber == 6) {
                    $adjustedVaultNumber = 5;
                } 
                elseif ($vaultNumber == 7) {
                    $adjustedVaultNumber = 6;
                } 
                elseif ($vaultNumber == 8) {
                    $adjustedVaultNumber = 7;
                } 
                elseif ($vaultNumber == 9) {
                    $adjustedVaultNumber = 8;
                } 
                elseif ($vaultNumber == 10) {
                    $adjustedVaultNumber = 9;
                } 
                elseif ($vaultNumber == 11) {
                    $adjustedVaultNumber = 10;
                } 
                elseif ($vaultNumber == 12) {
                    $adjustedVaultNumber = 10;
                } 
                elseif ($vaultNumber == 13) {
                    $adjustedVaultNumber = 11;
                } 
                elseif ($vaultNumber == 14) {
                    $adjustedVaultNumber = 12;
                } 
                elseif ($vaultNumber == 15) {
                    $adjustedVaultNumber = 13;
                } 
                elseif ($vaultNumber == 16) {
                    $adjustedVaultNumber = 14;
                } 
                elseif ($vaultNumber == 17) {
                    $adjustedVaultNumber = 15;
                }

                $unitId = $floorNumber . chr(64 + $adjustedVaultNumber) . $levelNumber . '-' . $slotNumberPadded . $side;

                $unitNumSide = in_array($vaultNumber, [5, 6, 11, 12]) ? $vaultNumber : $vaultNumber;

                // Get the latest slot_number from the database before each insertion
                $latestSlotNumber = DB::table('columbary_slots')
                    ->where('floor_number', $floorNumber)
                    ->max('slot_number');

                // If there are no slots yet, start from 1

                DB::table('columbary_slots')->insert([
                    'unit_number' => $unitNumber,
                    'slot_number' => $slotNumber++,
                    'floor_number' => $floorNumber,
                    'vault_number' => $vaultNumber,
                    'level_number' => $levelNumber,
                    'price' => $levelPrices[$levelNumber],
                    'type' => $levelTypes[$levelNumber],
                    'status' => 'Available',
                    'side' => $side,
                    'unit_id' => $unitId,
                    'unit_num_side' => $unitNumSide,
                ]);
                
                $unitNumber++;
                $slotNumber++;
                $totalSlots++;
            }
        }

        // Update vault numbers and unit_num_side for specific vaults
        $vaultUpdates = [
            6 => 5,
            7 => 6,
            8 => 7,
            9 => 8,
            10 => 9,
            11 => 10,
            12 => 10,
            13 => 11,
            14 => 12,
            15 => 13,
            16 => 14,
            17 => 15,
        ];

        foreach ($vaultUpdates as $oldVaultNumber => $newVaultNumber) {
            $updatedRows = DB::table('columbary_slots')
                ->where('floor_number', 6)
                ->where('vault_number', $oldVaultNumber)
                ->update(['vault_number' => $newVaultNumber, 'unit_num_side' => $newVaultNumber]);

            if ($updatedRows > 0) {
                echo "Successfully updated $updatedRows rows where floor_number is 6 and vault_number is $oldVaultNumber to vault_number $newVaultNumber.\n";
            } else {
                echo "No rows were updated where floor_number is 6 and vault_number is $oldVaultNumber.\n";
            }
        }

        echo "Total slot_number generated: $totalSlots\n";
    }
}