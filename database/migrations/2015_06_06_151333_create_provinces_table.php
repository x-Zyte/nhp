<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvincesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('provinces', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('name',50);
            $table->integer('sequence')->unsigned()->default(0);
            $table->boolean('active')->default(true);

            $table->integer('createdby')->unsigned();
            $table->dateTime('createddate');
            $table->integer('modifiedby')->unsigned();
            $table->dateTime('modifieddate');

            $table->engine = 'InnoDB';
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('provinces');
	}

}
