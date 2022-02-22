<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToExaminationResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('examination_results', function (Blueprint $table) {
            $table->foreign('trainee_id', 'examination_results_fk_trainee_id')
                ->references('id')
                ->on('trainees')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('examination_id', 'examination_results_fk_examination_id')
                ->references('id')
                ->on('examinations')
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
        Schema::table('examination_results', function (Blueprint $table) {
            $table->dropForeign('examination_results_fk_user_id');
            $table->dropForeign('examination_results_fk_trainee_id');
            $table->dropForeign('examination_results_fk_examination_id');
        });
    }
}
