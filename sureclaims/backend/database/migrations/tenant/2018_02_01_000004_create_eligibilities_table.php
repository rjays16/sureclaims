<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEligibilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eligibilities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('patient_id')
                ->nullable();
            $table->dateTime('checked_at')
                ->nullable();
            $table->boolean('is_ok')
                ->nullable();
            $table->longText('result_data')
                ->nullable();

            $table->timestamps();
            $table->string('created_by', 50)
                ->nullable()
                ->default(null);
            $table->string('updated_by', 50)
                ->nullable()
                ->default(null);

            // Add foreign keys
            $table->foreign('patient_id')
                ->references('id')->on('persons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
