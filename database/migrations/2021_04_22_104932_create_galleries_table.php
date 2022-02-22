<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    public function up()
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('gallery_category_id');
            $table->unsignedTinyInteger('content_type')
                ->comment('1 => Image, 2 => Video');
            $table->string('content_title', 191)->nullable();
            $table->string('content_path', 191);
            $table->unsignedInteger('institute_id');
            $table->unsignedTinyInteger('is_youtube_video')->default(0);
            $table->dateTime('publish_date')->nullable();
            $table->dateTime('archive_date')->nullable();
            $table->string('you_tube_video_id', 191)->nullable();
            $table->unsignedTinyInteger('row_status')->default(1);
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
        Schema::dropIfExists('galleries');
    }
}
