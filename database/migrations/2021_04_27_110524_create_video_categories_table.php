<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('institute_id');
            $table->string('title', 191);
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedTinyInteger('row_status')->default(1);
            $table->timestamps();
            $table->foreign('institute_id', 'video_categories_fk_institute_id')->references('id')->on('institutes')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('parent_id', 'video_categories_fk_parent_id')->references('id')->on('video_categories')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video_categories');
    }
}
