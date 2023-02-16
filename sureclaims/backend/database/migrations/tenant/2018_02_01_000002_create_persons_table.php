<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('last_name', 150)
                ->nullable();
            $table->string('first_name', 150)
                ->nullable();
            $table->string('middle_name', 150)
                ->nullable();

            $table->string('last_name_metaphone', 20)
                ->nullable();
            $table->string('first_name_metaphone', 20)
                ->nullable();
            $table->string('middle_name_metaphone', 20)
                ->nullable();

            $table->string('suffix', 50)
                ->nullable();
            $table->date('birth_date')
                ->nullable();
            $table->char('sex')
                ->nullable();
            $table->text('email_address', 150)
                ->nullable();
            $table->text('mailing_address', 255)
                ->nullable();
            $table->string('zip_code', 10)
                ->nullable();
            $table->string('land_line_no', 50)
                ->nullable();
            $table->string('mobile_no', 50)
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


            $table->index('last_name');
            $table->index('first_name');
            $table->index([
                'last_name_metaphone',
                'first_name_metaphone',
                'middle_name_metaphone',
                'birth_date',
            ], 'persons_metaphones_birth_date_index');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persons');
    }
}
