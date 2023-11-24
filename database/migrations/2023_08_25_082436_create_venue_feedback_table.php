<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVenueFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venue_feedback', function (Blueprint $table) {
            $table->id();
            $table->integer('venue_id');
            $table->integer('user_id');
            $table->tinyInteger('status')->default(0)->comment('1: Like, 2: Dislike, 0: Default');
            $table->string('feedback_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('venue_feedback');
    }
}