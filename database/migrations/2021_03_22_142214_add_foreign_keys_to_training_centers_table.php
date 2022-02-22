<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTrainingCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_centers', function (Blueprint $table) {
            $table->foreign('branch_id', 'training_centers_fk_branch_id')->references('id')->on('branches')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('institute_id', 'training_centers_fk_institute_id')->references('id')->on('institutes')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_centers', function (Blueprint $table) {
            $table->dropForeign('training_centers_fk_branch_id');
            $table->dropForeign('training_centers_fk_institute_id');
        });
    }
}
