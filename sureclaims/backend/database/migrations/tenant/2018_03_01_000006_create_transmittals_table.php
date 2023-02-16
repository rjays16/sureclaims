<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTransmittalsTable.
 */
class CreateTransmittalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transmittals', function (Blueprint $table) {
            $table->increments('id');

            $table->string('transmittal_no', 50)
                ->nullable();

            $table->date('transmit_date')
                ->nullable();
            $table->time('transmit_time')
                ->nullable();
            $table->string('transmittal_control_code', 50)
                ->nullable();
            $table->string('receipt_ticket_no', 50)
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->text('transmit_errors')
                ->nullable();

            $table->boolean('auto_transmit')
                ->default(0);


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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transmittals');
    }
}
