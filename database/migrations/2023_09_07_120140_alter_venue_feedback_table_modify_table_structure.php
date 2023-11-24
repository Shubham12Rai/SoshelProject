<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVenueFeedbackTableModifyTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('venue_feedback', function (Blueprint $table) {
            $table->dropColumn('venue_id');
            $table->string('place_id');
            $table->string('place_name');
            $table->string('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('venue_feedback', function (Blueprint $table) {
            $table->integer('venue_id');
            $table->dropColumn('place_name');
            $table->dropColumn('address');
            $table->dropColumn('place_id');
        });
    }
}
