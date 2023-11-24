<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEventFeedbackTableModifyTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('event_feedback', 'event_join_status');

        Schema::table('event_join_status', function (Blueprint $table) {
           $table->dropColumn('feedback_id');
           $table->dropColumn('feedback_reason');
           $table->bigInteger('join_status')->unsigned()->default(0)->comment("0:null,1:join,2:not_interested");
           $table->bigInteger('status')->unsigned()->default(1);
           $table->string('created_by')->nullable();
           $table->string('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('event_join_status', 'event_feedback');

        Schema::table('event_feedback', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
            $table->dropColumn('status');
            $table->smallInteger('feedback_id');
            $table->string('feedback_reason')->nullable();
         });
    }
}
