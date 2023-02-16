<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('person_id')
                ->nullable();

            $table->unsignedInteger('principal_id')
                ->nullable();
            $table->char('relation', 10)
                ->nullable();

            $table->string('pin', 50)
                ->nullable();
            $table->char('membership_type', 10)
                ->nullable();
            $table->string('pen', 50)
                ->nullable();
            $table->string('employer_name', 255)
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
            $table->foreign('person_id')
                ->references('id')->on('persons');
            $table->foreign('principal_id')
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
