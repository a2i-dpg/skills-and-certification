<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menu_items', function(Blueprint $table)
		{
            /** Please select engine that support transaction */
            $table->engine = 'InnoDB';

            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('menu_id')->nullable();
            $table->string('title', 191)->nullable();
            $table->string('title_lang_key', 255)->nullable();
            $table->string('permission_key', 191)->nullable();
            $table->string('url', 191);
            $table->string('target', 191)->default('_self');
            $table->string('icon_class', 191)->nullable();
            $table->string('color', 191)->nullable();
            $table->unsignedMediumInteger('parent_id')->nullable();
            $table->integer('order');
            $table->string('route', 191)->nullable();
            $table->text('parameters')->nullable();
            $table->timestamps();

            $table->foreign('menu_id')
                ->references('id')
                ->on('menus')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');

            $table->foreign('parent_id')
                ->references('id')
                ->on('menu_items')
                ->onDelete('SET NULL')
                ->onUpdate('CASCADE');
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('menu_items');
	}

}
