<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContractsTable extends Migration {

	public function up()
	{
		Schema::create('contracts', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->timestamp('created_at');
			$table->timestamp('updated_at');
			$table->integer('last_updated_by')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('contracts');
	}
}