<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarInspectionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_inspections', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('carid')->unsigned();
            $table->foreign('carid')->references('id')->on('cars');
            $table->string('irno',50);
            $table->dateTime('irdate');
            $table->string('laneno',50);
            $table->dateTime('lanedate');
            $table->string('zone',50);
            $table->string('from',50)->nullable();
            $table->string('to',50);
            $table->string('trailergroup',50);
            $table->string('trailercompany',50);
            $table->text('remark')->nullable();
            $table->enum('uptype', ['เดินหน้าขึ้น', 'ถอยหลังขึ้น']);
            $table->enum('position', ['บน1', 'บน2', 'บน3', 'บน4', 'ล่าง1', 'ล่าง2', 'ล่าง3', 'ล่าง4']);
            $table->integer('senderkilometer')->unsigned();
            $table->string('sendername',100);
            $table->dateTime('senderdate');
            $table->integer('driverkilometer')->unsigned();
            $table->string('drivername',100);
            $table->dateTime('driverdate');
            $table->integer('receiverkilometer')->unsigned();
            $table->integer('receiver')->unsigned();
            $table->foreign('receiver')->references('id')->on('employees');
            $table->dateTime('receiverdate');

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
		Schema::drop('car_inspections');
	}

}
