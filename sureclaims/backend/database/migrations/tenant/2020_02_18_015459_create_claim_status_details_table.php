<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateClaimStatusDetailsTable.
 */
class CreateClaimStatusDetailsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('claim_status_details', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('patient_id')
                ->nullable();
            $table->unsignedInteger('claim_id')
                ->nullable();

            $table->dateTime('date_inquiry')
                ->nullable();
            $table->date('as_of_date')
                ->nullable();
            $table->time('as_of_time')
                ->nullable();
            $table->date('claim_date_refile')
                ->nullable();
            $table->date('claim_date_received')
                ->nullable();
            $table->longText('data_json')
                ->nullable();

            $table->timestamps();

            // Add foreign keys
            $table->foreign('patient_id')
                ->references('id')->on('persons');
            $table->foreign('claim_id')
                ->references('id')->on('claims');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('claim_status_details');
	}
}
