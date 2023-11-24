<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterIncomeLevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('income_level', function (Blueprint $table) {
            $table->unsignedBigInteger('min_income');
            $table->unsignedBigInteger('max_income');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
        });
    }
}
