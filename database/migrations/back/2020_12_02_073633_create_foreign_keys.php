<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('users', function(Blueprint $table) {
			$table->foreign('role')->references('role')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('opportunities', function(Blueprint $table) {
			$table->foreign('requirement_category')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('opportunities', function(Blueprint $table) {
			$table->foreign('requirement_education')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('opportunities', function(Blueprint $table) {
			$table->foreign('requirement_type_of_contract')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('opportunities', function(Blueprint $table) {
			$table->foreign('requirement_prefered_major')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('opportunities', function(Blueprint $table) {
			$table->foreign('requirement_language')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('opportunities', function(Blueprint $table) {
			$table->foreign('requirement_location')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('opportunities', function(Blueprint $table) {
			$table->foreign('requirement_remote')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('opportunities', function(Blueprint $table) {
			$table->foreign('last_updated_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('categories', function(Blueprint $table) {
			$table->foreign('last_updated_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('educations', function(Blueprint $table) {
			$table->foreign('last_updated_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('contracts', function(Blueprint $table) {
			$table->foreign('last_updated_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('majors', function(Blueprint $table) {
			$table->foreign('last_updated_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('languages', function(Blueprint $table) {
			$table->foreign('last_updated_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('locations', function(Blueprint $table) {
			$table->foreign('last_updated_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('remotes', function(Blueprint $table) {
			$table->foreign('last_updated_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
	}

	public function down()
	{
		Schema::table('users', function(Blueprint $table) {
			$table->dropForeign('users_role_foreign');
		});
		Schema::table('opportunities', function(Blueprint $table) {
			$table->dropForeign('opportunities_requirement_category_foreign');
		});
		Schema::table('opportunities', function(Blueprint $table) {
			$table->dropForeign('opportunities_requirement_education_foreign');
		});
		Schema::table('opportunities', function(Blueprint $table) {
			$table->dropForeign('opportunities_requirement_type_of_contract_foreign');
		});
		Schema::table('opportunities', function(Blueprint $table) {
			$table->dropForeign('opportunities_requirement_prefered_major_foreign');
		});
		Schema::table('opportunities', function(Blueprint $table) {
			$table->dropForeign('opportunities_requirement_language_foreign');
		});
		Schema::table('opportunities', function(Blueprint $table) {
			$table->dropForeign('opportunities_requirement_location_foreign');
		});
		Schema::table('opportunities', function(Blueprint $table) {
			$table->dropForeign('opportunities_requirement_remote_foreign');
		});
		Schema::table('opportunities', function(Blueprint $table) {
			$table->dropForeign('opportunities_last_updated_by_foreign');
		});
		Schema::table('categories', function(Blueprint $table) {
			$table->dropForeign('categories_last_updated_by_foreign');
		});
		Schema::table('educations', function(Blueprint $table) {
			$table->dropForeign('educations_last_updated_by_foreign');
		});
		Schema::table('contracts', function(Blueprint $table) {
			$table->dropForeign('contracts_last_updated_by_foreign');
		});
		Schema::table('majors', function(Blueprint $table) {
			$table->dropForeign('majors_last_updated_by_foreign');
		});
		Schema::table('languages', function(Blueprint $table) {
			$table->dropForeign('languages_last_updated_by_foreign');
		});
		Schema::table('locations', function(Blueprint $table) {
			$table->dropForeign('locations_last_updated_by_foreign');
		});
		Schema::table('remotes', function(Blueprint $table) {
			$table->dropForeign('remotes_last_updated_by_foreign');
		});
	}
}