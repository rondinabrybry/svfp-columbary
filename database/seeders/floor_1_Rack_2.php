<?php

namespace Database\Seeders;

use App\Models\ColumbarySlot;
use Illuminate\Database\Seeder;

class floor_1_Rack_2 extends Seeder
{
    public function run()
    {
        $floors = 1; // Number of floors
        $rackPerFloor = 2; // Number of racks per floor
        $rowsPerRack = 50; // Number of rows per rack (increased from 6)

        $slotNumber = 1;

        for ($floor = 1; $floor <= $floors; $floor++) {
            for ($vault = 1; $vault <= $rackPerFloor; $vault++) {
                for ($row = 1; $row <= $rowsPerRack; $row++) {
                    // Determine the price based on the row level
                    if ($row === 7 || $row === 8) {
                        $price = 20000; // Price for middle rows
                    } else {
                        $price = 10000; // Price for other rows
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