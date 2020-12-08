<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOpportunitiesTable extends Migration {

	public function up()
	{
		Schema::create('opportunities', function(Blueprint $table) {
			$table->increments('id');
			$table->string('position_title');
			$table->string('position_category');
			$table->text('job_description');
			$table->integer('requirement_category')->unsigned();
			$table->integer('requirement_education')->unsigned();
			$table->integer('requirement_years_of_experience');
			$table->integer('requirement_type_of_contract')->unsigned();
			$table->integer('requirement_prefered_major')->unsigned();
			$table->integer('requirement_language')->unsigned();
			$table->integer('requirement_location')->unsigned();
			$table->integer('requirement_remote')->unsigned();
			$table->text('requirement_other_skills');
			$table->timestamp('created_at');
			$table->timestamp('updated_at');
			$table->integer('last_updated_by')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('opportunities');
	}
}