<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('columbary_slot_id');
            $table->string('unit_id');
            $table->decimal('unit_price', 8, 2);
            $table->string('buyer_name');
            $table->string('buyer_email');
            $table->string('payment_status');
            $table->decimal('price', 8, 2);
            $table->timestamp('purchase_date');
            $table->timestamps();

            $table->foreign('columbary_slot_id')->references('id')->on('columbary_slots')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}