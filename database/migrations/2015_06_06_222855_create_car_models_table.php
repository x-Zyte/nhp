<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarModelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_models', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('cartypeid')->unsigned();
            $table->foreign('cartypeid')->references('id')->on('car_types');
            $table->string('name',50);
            $table->decimal('registercost', 10, 2);
            $table->text('detail')->nullable();
            $table->boolean('active')->default(true);

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
		Schema::drop('car_models');
	}

}
