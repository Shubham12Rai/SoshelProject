<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->string('job_role')->nullable();
            $table->double('income_level')->nullable();
            $table->string('school_college')->nullable();
            $table->bigInteger('relationship_type_id')->unsigned()->nullable();
            $table->bigInteger('covid_vaccine_status_id')->unsigned()->nullable();
            $table->bigInteger('language_id')->unsigned()->nullable();
            $table->bigInteger('religious_id')->unsigned()->nullable();
            $table->bigInteger('family_plan_id')->unsigned()->nullable();
            $table->bigInteger('zodiac_sign_id')->unsigned()->nullable();
            $table->bigInteger('politics_id')->unsigned()->nullable();
            $table->string('pronouns')->nullable();
            $table->bigInteger('sports_id')->unsigned()->nullable();
            $table->bigInteger('music_id')->unsigned()->nullable();
            $table->bigInteger('going_out_id')->unsigned()->nullable();
            $table->bigInteger('pets_id')->unsigned()->nullable();
            $table->string('bio')->nullable();
            $table->bigInteger('status')->unsigned()->default(1);
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();

            //foreign keys
            $table->foreign('status')->references('id')->on('status')->onDelete('cascade');
            $table->foreign('relationship_type_id')->references('id')->on('relationship_type')->onDelete('cascade');
            $table->foreign('covid_vaccine_status_id')->references('id')->on('covid_vaccine_status')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('language')->onDelete('cascade');
            $table->foreign('religious_id')->references('id')->on('religious')->onDelete('cascade');
            $table->foreign('family_plan_id')->references('id')->on('family_plans')->onDelete('cascade');
            $table->foreign('zodiac_sign_id')->references('id')->on('zodiac')->onDelete('cascade');
            $table->foreign('politics_id')->references('id')->on('politics')->onDelete('cascade');
            $table->foreign('sports_id')->references('id')->on('sports')->onDelete('cascade');
            $table->foreign('music_id')->references('id')->on('musics')->onDelete('cascade');
            $table->foreign('going_out_id')->references('id')->on('going_out')->onDelete('cascade');
            $table->foreign('pets_id')->references('id')->on('pets')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_details');
    }
}
