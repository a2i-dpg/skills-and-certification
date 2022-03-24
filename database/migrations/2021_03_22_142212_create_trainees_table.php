<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraineesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainees', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('name', 191);
            $table->string('mobile', 20)->nullable()->index('trainee_mobile');
            $table->string('email', 191)->index('trainee_email')->unique();
            $table->mediumText('address')->nullable();
            $table->dateTime('date_of_birth')->nullable();
            $table->unsignedTinyInteger('gender')->nullable();
            $table->unsignedTinyInteger('disable_status')->nullable()->comment('1 => yes(disable) , 2 => no(not disable)');
            $table->unsignedTinyInteger('ethnic_group')->nullable()->default(2);
            $table->string('signature_pic')->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
            $table->unsignedTinyInteger('row_status')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trainees');
    }
}
