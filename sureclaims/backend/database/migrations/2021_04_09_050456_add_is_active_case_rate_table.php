<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsActiveCaseRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('case_rates', function (Blueprint $table)
        {
            $table->tinyInteger('is_active')
                ->after('case_type')
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('case_rates', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
}
