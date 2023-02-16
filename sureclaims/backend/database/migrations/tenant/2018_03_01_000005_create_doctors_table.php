<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->increments('id');

            $table->string('pan', 50)
                ->nullable();
            $table->string('tin', 50)
                ->nullable();

            $table->string('last_name', 255)
                ->nullable();
            $table->string('first_name', 255)
                ->nullable();
            $table->string('middle_name', 255)
                ->nullable();
            $table->string('suffix', 255)
                ->nullable();
            $table->date('birth_date')
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctors');
    }
}
