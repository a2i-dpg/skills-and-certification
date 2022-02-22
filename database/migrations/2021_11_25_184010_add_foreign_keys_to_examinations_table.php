<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToExaminationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('examinations', function (Blueprint $table) {
            $table->foreign('institute_id', 'examinations_fk_institute_id')
                ->references('id')
                ->on('institutes')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('batch_id', 'examinations_fk_batch_id')
                ->references('id')
                ->on('batches')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('training_center_id', 'examinations_fk_training_center_id')
                ->references('id')
                ->on('training_centers')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('examination_type_id', 'examinations_fk_examination_type_id')
                ->references('id')
                ->on('examination_types')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('user_id', 'examination_results_fk_user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('examinations', function (Blueprint $table) {
            $table->dropForeign('examinations_fk_batch_id');
            $table->dropForeign('examinations_fk_training_center_id');
            $table->dropForeign('examinations_fk_examination_type_id');
        });
    }
}
