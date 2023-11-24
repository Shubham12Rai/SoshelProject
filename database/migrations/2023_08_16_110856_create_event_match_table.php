<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventMatchTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_match', function (Blueprint $table) {
			$table->id();
			$table->integer('from');
			$table->integer('to');
			$table->timestamps();
		});
	}
}
