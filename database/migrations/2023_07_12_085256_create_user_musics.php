<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMusics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_musics', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('music_id')->nullable();
            $table->integer('status')->unsigned()->default(1);
            $table->timestamps();

            //foreign keys
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('music_id')->references('id')->on('musics')->onDelete('cascade');
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
        Schema::dropIfExists('user_musics');
    }
}
