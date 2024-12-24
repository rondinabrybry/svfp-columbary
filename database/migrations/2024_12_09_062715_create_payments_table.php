<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('columbary_slot_id')->constrained()->onDelete('cascade');
            $table->string('buyer_name');
            $table->string('buyer_address');
            $table->string('buyer_email');
            $table->string('contact_info');
            $table->enum('payment_status', ['Not Paid', 'Reserved', 'Paid'])->default('Not Paid');
            $table->decimal('price', 10, 2); // Add price column
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
