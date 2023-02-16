<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlobalLookupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('global_lookups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('domain_name', 191)->nullable();
            $table->string('lookup_type', 50)->nullable();
            $table->string('lookup_value', 191)->nullable();

            $table->index(['domain_name', 'lookup_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('global_lookups');
    }
}
