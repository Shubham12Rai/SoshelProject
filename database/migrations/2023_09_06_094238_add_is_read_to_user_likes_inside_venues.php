<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsReadToUserLikesInsideVenues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_likes_inside_venues', function (Blueprint $table) {
            $table->integer('is_read')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_likes_inside_venues', function (Blueprint $table) {
            $table->dropColumn('is_read');
        });
    }
}
