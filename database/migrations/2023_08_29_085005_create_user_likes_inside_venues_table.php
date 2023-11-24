<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLikesInsideVenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_likes_inside_venues', function (Blueprint $table) {
            $table->id();
			$table->integer('from');
			$table->integer('to');
			$table->smallInteger('status')->comment('0=>dislike,1=>like')->default(1);
            $table->timestamps();
        });
    }

}
