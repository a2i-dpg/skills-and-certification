<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToExaminationRoutinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('examination_routines', function (Blueprint $table) {
            $table->foreign('institute_id', 'examination_routines_fk_institute_id')
                ->references('id')
                ->on('institutes')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('batch_id', 'examination_routines_fk_batch_id')
                ->references('id')
                ->on('batches')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('training_center_id', 'examination_routines_fk_training_center_id')
                ->references('id')
                ->on('training_centers')
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
        Schema::table('examination_routines', function (Blueprint $table) {
            $table->dropForeign('examination_routines_fk_institute_id');
            $table->dropForeign('examination_routines_fk_batch_id');
            $table->dropForeign('examination_routines_fk_training_center_id');
        });
    }
}
