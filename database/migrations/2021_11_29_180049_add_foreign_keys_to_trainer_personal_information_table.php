<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTrainerPersonalInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainer_personal_information', function (Blueprint $table) {
            $table->foreign('trainer_id', 'trainer_personal_information_fk_trainer_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('institute_id', 'trainer_personal_information_fk_institute_id')
                ->references('id')
                ->on('institutes')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trainer_personal_information', function (Blueprint $table) {
            $table->dropForeign('trainer_personal_information_fk_trainer_id');
            $table->dropForeign('trainer_personal_information_fk_institute_id');
        });
    }
}
