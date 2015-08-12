<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cars', function(Blueprint $table)
		{
            $table->increments('id');

            $table->integer('branchid')->unsigned();
            $table->foreign('branchid')->references('id')->on('branches');
            $table->integer('cartypeid')->unsigned();
            $table->foreign('cartypeid')->references('id')->on('car_types');
            $table->string('no',10);
            $table->dateTime('dodate');
            $table->dateTime('receiveddate');
            $table->string('engineno',50);
            $table->string('chassisno',50);
            $table->integer('keyno')->unsigned();
            $table->string('model',50);
            $table->string('submodel',50);
            $table->string('colour',10);
            $table->boolean('iscompanycar')->default(false);

            $table->integer('createdby')->unsigned();
            $table->foreign('createdby')->references('id')->on('employees');
            $table->dateTime('createddate');
            $table->integer('modifiedby')->unsigned();
            $table->foreign('modifiedby')->references('id')->on('employees');
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
		Schema::drop('cars');
	}

}
