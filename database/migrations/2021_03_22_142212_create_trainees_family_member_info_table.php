<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraineesFamilyMemberInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainees_family_member_info', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('trainee_id')->index('trainees_family_members_info_trainee_id');
            $table->string('name', 191)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->unsignedTinyInteger('relation_with_trainee');
            $table->string('relation', 191)->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->string('occupation', 191)->nullable();
            $table->date('date_of_birth')->nullable();
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
        Schema::dropIfExists('trainees_family_member_info');
    }
}
