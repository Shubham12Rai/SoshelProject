<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersInsideVenuesTableAndRemoveUnnecessaryEventTables extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('user_event_like');
		Schema::dropIfExists('reject_events');
		Schema::dropIfExists('event_likes');
		Schema::dropIfExists('users_event');
		Schema::dropIfExists('event_match');
		Schema::create('users_inside_venues', function (Blueprint $table) {
			$table->id();
			$table->integer('user_id');
			$table->string('place_id');
			$table->timestamps();
		});
	}
}
