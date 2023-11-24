<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserEventLikeStatusToLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasColumn('user_event_like', 'status')) {
            Schema::table('user_event_like', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }

}