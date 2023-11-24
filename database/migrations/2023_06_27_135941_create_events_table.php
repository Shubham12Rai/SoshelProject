<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();  
            $table->string('title')->nullable();  
            $table->bigInteger('user_plan_id')->unsigned()->default(1);  
            $table->string('description');  
            $table->string('start_date_time'); 
            $table->string('end_date_time');  
            $table->string('address_on');  
            $table->bigInteger('status')->unsigned()->default(1);
            $table->timestamps();            
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();

            //foreign keys
            $table->foreign('status')->references('id')->on('status')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_plan_id')->references('id')->on('plan')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
