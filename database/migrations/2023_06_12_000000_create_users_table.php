<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('full_name')->nullable();
            $table->string('mobile_number', 15)->default(0);
            $table->string('email_id')->nullable();
            $table->string('password')->nullable();
            $table->string('otp')->nullable();
            $table->integer('otp_verified_status')->default(0);
            $table->datetime('otp_expire_time')->nullable();
            $table->string('gender')->nullable();
            $table->integer('height_in_inch')->default(0);
            $table->integer('height_in_feet')->default(0);
            $table->bigInteger('ethnicity_id')->nullable();
            $table->integer('sexuality_id')->nullable();
            $table->integer('dating_intention_id')->nullable();
            $table->integer('education_status_id')->nullable();
            $table->integer('plan_id')->nullable();
            $table->string('google_id')->nullable();
            $table->string('apple_id')->nullable();
            $table->string('instagram_id')->nullable();
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
            $table->integer('active_status')->default(1);
            $table->string('dob')->nullable();
            $table->string('job_role')->nullable();
            $table->string('income_level')->nullable();
            $table->string('school_college_name')->nullable();
            $table->integer('relationship_type_id')->nullable();
            $table->integer('covid_vaccine_id')->nullable();
            $table->integer('family_plan_id')->nullable();
            $table->integer('zodiac_sign_id')->nullable();
            $table->integer('politics_id')->nullable();
            $table->string('pronouns')->nullable();
            $table->string('bio')->nullable();
            $table->integer('terms_condition')->default(0);
            $table->integer('privacy_policy')->default(0);
            $table->integer('religious_id')->nullable();
            $table->string('interested_in')->nullable();
            $table->string('interested_for')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            //foreign keys
            // $table->foreign('ethnicity_id')->references('id')->on('ethnicity')->onDelete('cascade');
            // $table->foreign('sexuality_id')->references('id')->on('sexuality')->onDelete('cascade');
            // $table->foreign('dating_intention_id')->references('id')->on('dating_intention')->onDelete('cascade');
            // $table->foreign('education_status_id')->references('id')->on('education_status')->onDelete('cascade');
            // $table->foreign('plan_id')->references('id')->on('plan')->onDelete('cascade');
            // $table->foreign('relationship_type_id')->references('id')->on('relationship_type')->onDelete('cascade');
            // $table->foreign('covid_vaccine_id')->references('id')->on('covid_vaccine_status')->onDelete('cascade');
            // $table->foreign('family_plan_id')->references('id')->on('family_plans')->onDelete('cascade');
            // $table->foreign('zodiac_sign_id')->references('id')->on('zodiac')->onDelete('cascade');
            // $table->foreign('politics_id')->references('id')->on('politics')->onDelete('cascade');
            // $table->foreign('religious_id')->references('id')->on('religious')->onDelete('cascade');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}