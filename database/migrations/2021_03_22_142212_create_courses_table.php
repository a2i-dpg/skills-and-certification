<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('institute_id')->index('courses_fk_institute_id');
            $table->unsignedInteger('programme_id')->nullable()->index('courses_fk_programme_id');
            $table->string('title', 191);
            $table->string('code', 191);
            $table->unsignedDouble('course_fee', 11, 2)->default(0);
            $table->string('duration', 30)->nullable();
            $table->unsignedMediumInteger('total_seat')->default(0);
            $table->text('description')->nullable();
            $table->text('target_group')->nullable();
            $table->text('objects')->nullable();
            $table->text('contents')->nullable();
            $table->text('training_methodology')->nullable();
            $table->text('evaluation_system')->nullable();
            $table->text('prerequisite')->nullable();
            $table->text('eligibility')->nullable();
            $table->string('cover_image', 191)->nullable();
            $table->json('application_form_settings')->nullable();
            $table->unsignedTinyInteger('row_status')->nullable()->default(1);
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
        Schema::table('courses', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
