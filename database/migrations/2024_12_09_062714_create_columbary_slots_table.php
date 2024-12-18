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
            $table->integer('slot_number');
            $table->index('slot_number'); 
            $table->integer('floor_number');
            $table->index('floor_number');
            $table->integer('vault_number');
            $table->index('vault_number');
            $table->enum('status', ['Available', 'Reserved', 'Sold', 'Not Available'])->default('Available');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('columbary_slots');
    }
}
