<?php

namespace Database\Seeders;

use App\Models\ColumbarySlot;
use Illuminate\Database\Seeder;

class ColumbarySlotSeeder extends Seeder
{
    public function run()
    {
        foreach (range(1, 10) as $index) {
            ColumbarySlot::create([
                'slot_number' => $index,
                'price' => rand(50000, 100000),
            ]);
        }
    }
}
