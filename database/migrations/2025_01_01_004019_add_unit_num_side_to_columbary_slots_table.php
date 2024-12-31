<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitNumSideToColumbarySlotsTable extends Migration
{
    public function up()
    {
        Schema::table('columbary_slots', function (Blueprint $table) {
            $table->string('unit_num_side')->after('unit_id');
        });
    }

    public function down()
    {
        Schema::table('columbary_slots', function (Blueprint $table) {
            $table->dropColumn('unit_num_side');
        });
    }
}