<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ServiceGoogleDataSave extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_google_data_save', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('place_name');
            $table->string('place_id')->unique();
            $table->string('vicinity');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->text('json_object');
            $table->bigInteger('status')->unsigned()->default(1);
            $table->bigInteger('service_type_id')->unsigned()->default(1)->index();;
            $table->bigInteger('service_city_id')->unsigned();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();

            //foreign keys
            $table->foreign('status')->references('id')->on('status')->onDelete('cascade');
            $table->foreign('service_type_id')->references('id')->on('service_type')->onDelete('cascade');
            $table->foreign('service_city_id')->references('id')->on('service_cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('service_google_data_save');
    }
}
