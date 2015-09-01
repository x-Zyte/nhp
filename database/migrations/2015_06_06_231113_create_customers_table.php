<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('title',10);
            $table->string('firstname',50);
            $table->string('lastname',50)->nullable();
            $table->text('address')->nullable();
            $table->integer('districtid')->unsigned()->nullable();
            $table->foreign('districtid')->references('id')->on('districts');
            $table->integer('amphurid')->unsigned()->nullable();
            $table->foreign('amphurid')->references('id')->on('amphurs');
            $table->integer('provinceid')->unsigned()->nullable();
            $table->foreign('provinceid')->references('id')->on('provinces');
            $table->string('zipcode',5)->nullable();
            $table->string('email',100)->nullable();
            $table->string('phone',20)->nullable();
            $table->integer('branchid')->unsigned();
            $table->foreign('branchid')->references('id')->on('branchs');

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
		Schema::drop('customers');
	}

}
