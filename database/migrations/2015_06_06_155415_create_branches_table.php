<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('branches', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('provinceid')->unsigned();
            $table->foreign('provinceid')->references('id')->on('provinces');
            $table->string('name',50);
            $table->text('detail')->nullable();
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
		Schema::drop('branches');
	}

}
