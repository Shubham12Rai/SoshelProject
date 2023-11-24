<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersInsideVenuesTableAddFiltersFields extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users_inside_venues', function (Blueprint $table) {
			$table->string('interested_in')->nullable();
			$table->string('interested_for')->nullable();
			$table->smallInteger('incognito_mode')->default(0)->comment("0:No,1:Yes");
		});
	}
}
