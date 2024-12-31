<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class floor1 extends Seeder
{
    public function run()
    {
        $maxVaultNumber = 12;
        $maxLevelNumber = 6;
        $floorNumber = 1;
        $unitNumber = 1;
        $slotNumber = 1;
        $totalSlots = 0;

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
            3 => 'L', // 3L
            4 => 'R', // 3R
            5 => 'R', //4
            6 => 'R', //5
            7 => 'L', //6
            8 => 'L', //7
            9 => 'L', // 8L
            10 => 'R', // 8R
            11 => 'R', //9
            12 => 'R', //10
        ];

        $vaultCounts = [
            1 => 168, //1
            2 => 360, //2
            3 => 168, // 3L
            4 => 168, // 3R
            5 => 360, //4
            6 => 168, //5
            7 => 96, //6
            8 => 192, //7
            9 => 96, // 8L
            10 => 96, // 8R
            11 => 192, //9
            12 => 96, //10
        ];

        $slotsPerLevel = [
            1 => 28, //1
            2 => 60, //2
            3 => 28, // 3L
            4 => 28, // 3R
            5 => 60, //4
            6 => 28, //5
            7 => 16, //6
            8 => 32, //7
            9 => 16, // 8L
            10 => 16, // 8R
            11 => 32, //9
            12 => 16, //10
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
                if ($vaultNumber == 4) {
                    $adjustedVaultNumber = 3;
                } elseif ($vaultNumber == 5) {
                    $adjustedVaultNumber = 4;
                } elseif ($vaultNumber == 6) {
                    $adjustedVaultNumber = 5;
                } elseif ($vaultNumber == 7) {
                    $adjustedVaultNumber = 6;
                } elseif ($vaultNumber == 8) {
                    $adjustedVaultNumber = 7;
                } elseif ($vaultNumber == 9) {
                    $adjustedVaultNumber = 8;
                } elseif ($vaultNumber == 10) {
                    $adjustedVaultNumber = 8;
                } elseif ($vaultNumber == 11) {
                    $adjustedVaultNumber = 9;
                } elseif ($vaultNumber == 12) {
                    $adjustedVaultNumber = 10;
                }

                $unitId = $floorNumber . chr(64 + $adjustedVaultNumber) . $levelNumber . '-' . $slotNumberPadded . $side;

                $unitNumSide = in_array($vaultNumber, [3, 4, 9, 10]) ? $vaultNumber : $vaultNumber;

                DB::table('columbary_slots')->insert([
                    'unit_number' => $unitNumber,
                    'slot_number' => $slotNumber,
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

        $updatedRVault4 = DB::table('columbary_slots')
        ->where('floor_number', 1)
        ->where('vault_number', 4)
        ->update(['vault_number' => 3]);

        if ($updatedRVault4 > 0) {
            echo "Successfully updated $updatedRVault4 rows where floor_number is 1 and vault_number is 4 to vault_number 3.\n";
        } else {
            echo "No rows were updated where floor_number is 1 and vault_number is 4.\n";
        }


        $updatedRVault5 = DB::table('columbary_slots')
            ->where('floor_number', 1)
            ->where('vault_number', 5)
            ->update(['vault_number' => 4, 'unit_num_side' => 4]);

        if ($updatedRVault5 > 0) {
            echo "Successfully updated $updatedRVault5 rows where floor_number is 1 and vault_number is 5 to vault_number 4.\n";
        } else {
            echo "No rows were updated where floor_number is 1 and vault_number is 5.\n";
        }


        $updatedRVault6 = DB::table('columbary_slots')
            ->where('floor_number', 1)
            ->where('vault_number', 6)
            ->update(['vault_number' => 5, 'unit_num_side' => 5]);

        if ($updatedRVault6 > 0) {
            echo "Successfully updated $updatedRVault6 rows where floor_number is 1 and vault_number is 6 to vault_number 5.\n";
        } else {
            echo "No rows were updated where floor_number is 1 and vault_number is 6.\n";
        }


        $updatedRVault7 = DB::table('columbary_slots')
            ->where('floor_number', 1)
            ->where('vault_number', 7)
            ->update(['vault_number' => 6, 'unit_num_side' => 6]);

        if ($updatedRVault7 > 0) {
            echo "Successfully updated $updatedRVault7 rows where floor_number is 1 and vault_number is 7 to vault_number 6.\n";
        } else {
            echo "No rows were updated where floor_number is 1 and vault_number is 6.\n";
        }


        $updatedRVault8 = DB::table('columbary_slots')
            ->where('floor_number', 1)
            ->where('vault_number', 8)
            ->update(['vault_number' => 7, 'unit_num_side' => 7]);

        if ($updatedRVault8 > 0) {
            echo "Successfully updated $updatedRVault8 rows where floor_number is 1 and vault_number is 8 to vault_number 7.\n";
        } else {
            echo "No rows were updated where floor_number is 1 and vault_number is 6.\n";
        }


        $updatedRVault9 = DB::table('columbary_slots')
            ->where('floor_number', 1)
            ->where('vault_number', 9)
            ->update(['vault_number' => 8, 'unit_num_side' => 8]);

        if ($updatedRVault9 > 0) {
            echo "Successfully updated $updatedRVault9 rows where floor_number is 1 and vault_number is 9 to vault_number 8.\n";
        } else {
            echo "No rows were updated where floor_number is 1 and vault_number is 6.\n";
        }

        $updatedRVault10 = DB::table('columbary_slots')
            ->where('floor_number', 1)
            ->where('vault_number', 10)
            ->update(['vault_number' => 8, 'unit_num_side' => 8]);

        if ($updatedRVault10 > 0) {
            echo "Successfully updated $updatedRVault10 rows where floor_number is 1 and vault_number is 10 to vault_number 8.\n";
        } else {
            echo "No rows were updated where floor_number is 1 and vault_number is 10.\n";
        }

        $updatedRVault11 = DB::table('columbary_slots')
            ->where('floor_number', 1)
            ->where('vault_number', 11)
            ->update(['vault_number' => 9, 'unit_num_side' => 9]);

        if ($updatedRVault11 > 0) {
            echo "Successfully updated $updatedRVault11 rows where floor_number is 1 and vault_number is 11 to vault_number 9.\n";
        } else {
            echo "No rows were updated where floor_number is 1 and vault_number is 11.\n";
        }
        
        $updatedRVault12 = DB::table('columbary_slots')
            ->where('floor_number', 1)
            ->where('vault_number', 12)
            ->update(['vault_number' => 10, 'unit_num_side' => 10]);

        if ($updatedRVault12 > 0) {
            echo "Successfully updated $updatedRVault12 rows where floor_number is 1 and vault_number is 12 to vault_number 10.\n";
        } else {
            echo "No rows were updated where floor_number is 1 and vault_number is 12.\n";
        }

        echo "Total slot_number generated: $totalSlots\n";
    }
}