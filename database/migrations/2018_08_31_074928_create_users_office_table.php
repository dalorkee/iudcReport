<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersOfficeTable extends Migration
{
	/**
	* Run the migrations.
	* @return void
	*/
	public function up() {
		Schema::create('office', function (Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->string('off_name_full', 120)->nullable();;
			$table->string('off_name_short', 30)->nullable();;
			$table->string('off_name_en', 120)->nullable();;
		});
	}

	/**
	* Reverse the migrations.
	* @return void
	*/
	public function down() {
		Schema::dropIfExists('office');
	}
}
