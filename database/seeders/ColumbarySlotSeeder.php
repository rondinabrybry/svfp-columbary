<?php

namespace Database\Seeders;

use App\Models\ColumbarySlot;
use Illuminate\Database\Seeder;

class ColumbarySlotSeeder extends Seeder
{
    public function run()
    {
        $floors = 3; // Number of floors
        $vaultsPerFloor = 5; // Number of vaults per floor
        $slotsPerVault = 6; // Number of slots per vault

        $slotNumber = 1;

        for ($floor = 1; $floor <= $floors; $floor++) {
            for ($vault = 1; $vault <= $vaultsPerFloor; $vault++) {
                for ($slot = 1; $slot <= $slotsPerVault; $slot++) {
                    // Determine the price based on the slot level
                    if ($slot === 3 || $slot === 4) {
                        $price = 20000; // Price for middle slots
                    } else {
                        $price = 10000; // Price for other slots
                    }

                    ColumbarySlot::create([
                        'slot_number' => $slotNumber++,
                        'floor_number' => $floor,
                        'vault_number' => $vault,
                        'price' => $price, // Set the determined price
                        'status' => 'Available'
                    ]);
                }
            }
        }
    }
}
