<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTraineesFamilyMemberInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainees_family_member_info', function (Blueprint $table) {
            $table->foreign('trainee_id', 'trainees_family_members_info')->references('id')->on('trainees')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trainees_family_member_info', function (Blueprint $table) {
            $table->dropForeign('trainees_family_members_info');
        });
    }
}
