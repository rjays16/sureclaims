<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateReturnDocumentsTable.
 */
class CreateReturnDocumentsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('return_documents', function(Blueprint $table) {
            $table->increments('id');

            $table->string('file_system_id', 255)
                ->nullable();

            $table->unsignedInteger('patient_id')
                ->nullable();
            $table->unsignedInteger('claim_id')
                ->nullable();
            $table->unsignedInteger('support_document_id')
                ->nullable();

            $table->boolean('is_uploaded')
                ->default(0);

            $table->timestamps();

            // Add foreign keys
            $table->foreign('patient_id')
                ->references('id')->on('persons');
            $table->foreign('claim_id')
                ->references('id')->on('claims');
            $table->foreign('support_document_id')
                ->references('id')->on('supporting_documents');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('return_documents');
	}
}
