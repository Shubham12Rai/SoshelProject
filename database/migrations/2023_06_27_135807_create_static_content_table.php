<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaticContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('static_content', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('title')->nullable();            
            $table->text('description')->nullable();
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
        Schema::dropIfExists('static_content');
    }
}
