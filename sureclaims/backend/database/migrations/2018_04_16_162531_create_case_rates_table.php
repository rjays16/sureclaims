<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCaseRatesTable.
 */
class CreateCaseRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'case_rates', function ( Blueprint $table ) {
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
                ->comment( 'Primary HCI fee' );

            $table->decimal( 'prof_fee', 9, 2 )
                ->nullable()
                ->default( 0 )
                ->comment( 'Primary Professional fee' );

            $table->boolean( 'allow_second' )
                ->nullable()
                ->default( 0 )
                ->comment( 'If this primary case rate to have second case rate' );

            $table->decimal( 'secondary_hci_fee', 9, 2 )
                ->nullable()
                ->default( 0 )
                ->comment( 'Secondary HCI fee' );

            $table->decimal( 'secondary_prof_fee', 9, 2 )
                ->nullable()
                ->default( 0 )
                ->comment( 'Secondary Professional fee' );

            $table->date( 'effectivity_date' );

            $table->string( 'case_type', 10 )
                ->comment( 'Can be icd or rvs' );;

            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop( 'case_rates' );
    }
}
