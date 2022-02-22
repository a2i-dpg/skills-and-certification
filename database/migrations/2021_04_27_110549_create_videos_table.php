<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('institute_id');
            $table->unsignedInteger('video_category_id')->nullable();
            $table->string('title', 191);
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('video_type')->default(0)->comment('youtube => 1, uploaded => 2');
            $table->string('youtube_video_url', 255)->nullable();
            $table->string('youtube_video_id', 20)->nullable();
            $table->string('uploaded_video_path', 191)->nullable();
            $table->unsignedTinyInteger('row_status')->default(1);
            $table->timestamps();
            $table->foreign('institute_id', 'videos_fk_institute_id')->references('id')->on('institutes')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('video_category_id', 'videos_fk_video_category_id')->references('id')->on('video_categories')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
