<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateIndexQueryTablesTable.
 */
class CreateIndexQueryTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'index_query_tables', function ( Blueprint $table ) {
            $table->increments( 'id' );
            $table->string( 'query', 100 );
            $table->string( 'table', 20 );
            $table->timestamp( 'created_at' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop( 'index_query_tables' );
    }
}
