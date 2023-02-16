<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWithLateralityToRvsCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rvs_codes', function (Blueprint $table) {
            //
            Schema::table('rvs_codes', function (Blueprint $table)
            {
                $table->tinyInteger('with_laterality')
                    ->after('description')
                    ->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rvs_codes', function (Blueprint $table) {
            $table->dropColumn('with_laterality');
        });
    }
}
