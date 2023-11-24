<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUsersEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_event', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->string('interested_in');
            $table->string('interested_for');
            $table->smallInteger('incognito_mode')->default(0);
            $table->timestamps();
        });
    }

}
