<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColumbarySlotsTable extends Migration
{
    public function up()
    {
        Schema::create('columbary_slots', function (Blueprint $table) {
            $table->id();
            $table->string('unit_number', 3); // Change to string to accommodate prefixed zeros
            $table->integer('slot_number')->unique();
            $table->integer('floor_number');
            $table->integer('vault_number');
            $table->integer('level_number'); // Add level_number column
            $table->string('type')->nullable();
            $table->enum('status', ['Available', 'Reserved', 'Sold', 'Not Available'])->default('Available');
            $table->decimal('price', 10, 2);
            $table->string('side', 1); // Add side column
            $table->string('unit_id'); // Add unit_id column
            $table->timestamps();

            // Index the necessary columns with a shorter name
            $table->index(['slot_number', 'floor_number', 'vault_number', 'level_number'], 'slot_floor_vault_level_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('columbary_slots');
    }
}