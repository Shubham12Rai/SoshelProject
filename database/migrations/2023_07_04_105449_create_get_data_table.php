<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('get_data', function (Blueprint $table) {
            $table->id();
            $table->string('data')->nullable();
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
        Schema::dropIfExists('get_data');
    }
}
