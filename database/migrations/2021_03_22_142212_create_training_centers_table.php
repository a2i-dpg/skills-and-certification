<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_centers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 191);
            $table->unsignedInteger('institute_id')->index('training_centers_fk_institute_id');
            $table->unsignedInteger('branch_id')->nullable()->index('training_centers_fk_branch_id');
            $table->string('address', 191)->nullable();
            $table->text('google_map_src')->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('course_coordinator_signature', 255)->nullable();
            $table->string('course_director_signature',255)->nullable();
            $table->unsignedTinyInteger('row_status')->default(1)->comment('0 -> inactive, 1 ->active, 99->deleted');
            $table->unsignedInteger('created_by')->nullable();
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
        Schema::dropIfExists('training_centers');
    }
}
