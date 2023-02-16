<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateRvsCodesTable.
 */
class CreateRvsCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rvs_codes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code', 50)
                ->nullable();

            $table->text('description')
                ->nullable();

            $table->unsignedInteger('rvu')
                ->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->index('code' );

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rvs_codes');
    }
}
