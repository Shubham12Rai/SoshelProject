<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserGoingOut extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_going_out', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('going_out_id')->nullable();
            $table->integer('status')->unsigned()->default(1);
            $table->timestamps();

            //foreign keys
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('going_out_id')->references('id')->on('going_out')->onDelete('cascade');
            // $table->foreign('status')->references('id')->on('status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_going_out');
    }
}
