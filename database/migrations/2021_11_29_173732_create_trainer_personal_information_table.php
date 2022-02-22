<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainerPersonalInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainer_personal_information', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('trainer_id')->index('trainer_personal_information_fk_user_id');
            $table->unsignedInteger('institute_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('mobile');
            $table->unsignedTinyInteger('gender')->nullable();
            $table->unsignedTinyInteger('religion')->nullable();
            $table->string('nationality')->nullable();
            $table->dateTime('date_of_birth')->nullable();
            $table->string('nid_no')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('birth_registration_no')->nullable();
            $table->unsignedTinyInteger('marital_status')->nullable()->default(0);
            $table->string('profile_pic')->nullable();
            $table->string('signature_pic')->nullable();
            $table->string('present_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->unsignedTinyInteger('row_status')->nullable()->default(1);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trainer_personal_information');
    }
}
