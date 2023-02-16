<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateSupportingDocumentsTable.
 */
class CreateSupportingDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supporting_documents', function (Blueprint $table) {
            $table->increments('id');

            $table->string('file_system_id', 255)
                ->nullable();

            $table->unsignedInteger('patient_id')
                ->nullable();
            $table->unsignedInteger('claim_id')
                ->nullable();

            $table->string('storage_uid', 255)
                ->nullable();
            $table->string('document_type', 20)
                ->nullable();
            $table->string('file_name', 255)
                ->nullable();
            $table->unsignedBigInteger('file_size')
                ->nullable();
            $table->text('public_path')
                ->nullable();
            $table->string('hash', 100)
                ->nullable();
            $table->string('encrypted_hash', 100)
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
        Schema::drop('supporting_documents');
    }
}
