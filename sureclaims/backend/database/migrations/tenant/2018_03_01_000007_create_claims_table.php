<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateClaimsTable.
 */
class CreateClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('patient_id')
                ->nullable();
            $table->unsignedInteger('transmittal_id')
                ->nullable();

            $table->string('claim_no', 100)
                ->nullable();
            $table->string('lhio_series_no', 100)
                ->nullable();

            $table->date('admission_date')
                ->nullable();
            $table->date('discharge_date')
                ->nullable();

            $table->string('status', 50)
                ->nullable();

            $table->longText('data_json')
                ->nullable();
            $table->longText('data_xml')
                ->nullable();
            $table->boolean('is_valid')
                ->default(0);
            $table->text('validation_errors')
                ->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->string('created_by', 50)
                ->nullable()
                ->default(null);
            $table->string('updated_by', 50)
                ->nullable()
                ->default(null);
            $table->string('deleted_by', 50)
                ->nullable()
                ->default(null);

            // Add foreign keys
            $table->foreign('patient_id')
                ->references('id')->on('persons');
            $table->foreign('transmittal_id')
                ->references('id')->on('transmittals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('claims');
    }
}
