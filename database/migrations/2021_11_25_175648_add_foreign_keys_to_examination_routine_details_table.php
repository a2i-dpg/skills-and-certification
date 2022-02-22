<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToExaminationRoutineDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('examination_routine_details', function (Blueprint $table) {
            $table->foreign('examination_routine_id', 'examination_routine_details_fk_examination_routine_id')
                ->references('id')
                ->on('examination_routines')
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
        Schema::table('examination_routine_details', function (Blueprint $table) {
            $table->dropForeign('examination_routine_details_fk_examination_routine_id');
        });
    }
}
