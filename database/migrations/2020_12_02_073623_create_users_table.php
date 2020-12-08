<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->string('first_name');
			$table->string('last_name');
			$table->string('email');
			$table->string('password');
			$table->timestamp('created_at');
			$table->timestamp('updated_at');
			$table->integer('role')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}