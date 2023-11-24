<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeMinMaxIncomeNullableInIncomeLevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('income_level', function (Blueprint $table) {
            $table->unsignedBigInteger('min_income')->nullable()->change();
            $table->unsignedBigInteger('max_income')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('income_level', function (Blueprint $table) {
            $table->unsignedBigInteger('min_income')->nullable(false)->change();
            $table->unsignedBigInteger('max_income')->nullable(false)->change();
        });
    }
}
