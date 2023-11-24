<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEventsTableModifyTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign('events_user_id_foreign');
            $table->dropColumn('user_id');
            $table->renameColumn('user_plan_id','plan_id');
            $table->renameColumn('start_date_time','start_date');
            $table->renameColumn('end_date_time','end_date');
            $table->string('lat');
            $table->string('lon');
            $table->string('contact_no');
        });
    }

}
