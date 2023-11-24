<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableEventImagesDropForeignKey extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('event_images', function (Blueprint $table) {
			$table->dropForeign('event_images_status_foreign');
			$table->dropForeign('event_images_user_id_foreign');
		});
	}
}
