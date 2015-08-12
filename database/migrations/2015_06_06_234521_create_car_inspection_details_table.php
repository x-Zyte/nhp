<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarInspectionDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_inspection_details', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('carinspectionid')->unsigned();
            $table->foreign('carinspectionid')->references('id')->on('car_inspections');
            $table->integer('carchecklistid')->unsigned();
            $table->foreign('carchecklistid')->references('id')->on('car_checklists');
            $table->enum('sendercheck', ['OK', 'NG']);
            $table->enum('drivercheck', ['OK', 'NG']);
            $table->enum('receivercheck', ['OK', 'NG']);

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
		Schema::drop('car_inspection_details');
	}

}
