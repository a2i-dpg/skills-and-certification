<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleryCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('institute_id');
            $table->unsignedInteger('batch_id')->nullable();
            $table->unsignedInteger('programme_id')->nullable();
            $table->unsignedInteger('created_by');
            $table->string('title', 191);
            $table->string('image',191)->nullable();
            $table->boolean('featured')->default(false);
            $table->unsignedTinyInteger('row_status')->default(1);
            $table->timestamps();
            $table->foreign('institute_id', 'gallery_categories_fk_institute_id')
                ->references('id')
                ->on('institutes')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->foreign('batch_id', 'gallery_categories_fk_batch_id')
                ->references('id')
                ->on('batches')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->foreign('programme_id', 'gallery_categories_fk_programme_id')
                ->references('id')
                ->on('programmes')
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
        Schema::dropIfExists('gallery_categories');
    }
}
