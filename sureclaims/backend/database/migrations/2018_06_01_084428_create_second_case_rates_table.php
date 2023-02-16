<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecondCaseRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('second_case_rates', function (Blueprint $table) {
            $table->increments( 'id' );

            $table->string( 'code', 25 )
                ->comment( 'Case rate code' );

            $table->text( 'description' )
                ->nullable()
                ->comment( 'Case rate description' );

            $table->string( 'item_code', 25 )
                ->comment( 'ICD or RVS code depending on case_type' );

            $table->text( 'item_description' )
                ->nullable()
                ->comment( 'ICD or RVS description' );

            $table->decimal( 'hci_fee', 9, 2 )
                ->nullable()
                ->default( 0 )
                ->comment( 'HCI fee' );

            $table->decimal( 'prof_fee', 9, 2 )
                ->nullable()
                ->default( 0 )
                ->comment( 'Professional fee' );

            $table->date( 'effectivity_date' );

            $table->string( 'case_type', 10 )
                ->comment( 'Can be icd or rvs' );;

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('second_case_rates');
    }
}
