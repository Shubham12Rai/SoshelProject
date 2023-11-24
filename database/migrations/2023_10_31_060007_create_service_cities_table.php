<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_cities', function (Blueprint $table) {
            $table->id();
            $table->string('city_pointer_name');
            $table->string('city_name');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->bigInteger('radius')->unsigned()->default(4000);
            $table->bigInteger('status')->unsigned()->default(1);
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();

            //foreign keys
            $table->foreign('status')->references('id')->on('status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_cities');
    }
}
