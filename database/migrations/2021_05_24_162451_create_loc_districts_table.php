<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocDistrictsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('loc_districts', function(Blueprint $table)
		{
			$table->mediumIncrements('id');
			$table->mediumInteger('loc_division_id')->unsigned();
			$table->char('division_bbs_code', 4)->nullable();
			$table->string('title');
			$table->char('bbs_code', 4)->nullable();
			$table->boolean('row_status')->default(1);
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
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
		Schema::drop('loc_districts');
	}

}
