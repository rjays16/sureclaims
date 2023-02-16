<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'employers', function ( Blueprint $table ) {
            $table->increments( 'id' );
            $table->string( 'pen', 12 );
            $table->string( 'name', 500);
            $table->string( 'address' , 255);
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
        Schema::dropIfExists( 'employers' );
    }
}
