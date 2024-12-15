<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            floor1::class,
            floor2::class,
            floor3::class,
            floor4::class,
            floor5::class,
            floor6::class,
        ]);
    }
}
