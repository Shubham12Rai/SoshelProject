<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();            
            $table->string('user_name')->nullable();            
            $table->string('transcation_id')->nullable();            
            $table->string('mobile_no')->nullable();            
            $table->string('transaction_medium')->nullable();         
            $table->string('paid_for')->nullable();           
            $table->double('amount')->default(0);
            $table->bigInteger('status')->unsigned()->default(1);
            $table->timestamps();            
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();

            //foreign keys
            $table->foreign('status')->references('id')->on('status')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_details');
    }
}
