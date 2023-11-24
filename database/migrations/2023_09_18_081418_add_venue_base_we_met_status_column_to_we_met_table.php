<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVenueBaseWeMetStatusColumnToWeMetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('we_met', function (Blueprint $table) {
            $table->integer('venue_base_we_met_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('we_met', function (Blueprint $table) {
            $table->dropColumn('venue_base_we_met_status');
        });
    }
}
