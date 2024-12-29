<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalColumnsToReservationsTable extends Migration
{
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('floor_number')->after('unit_id');
            $table->string('vault_number')->after('floor_number');
            $table->string('level_number')->after('vault_number');
            $table->string('type')->after('level_number');
        });
    }

    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('floor_number');
            $table->dropColumn('vault_number');
            $table->dropColumn('level_number');
            $table->dropColumn('type');
        });
    }
}