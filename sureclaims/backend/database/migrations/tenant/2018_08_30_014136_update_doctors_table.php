<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'doctors',
            function (Blueprint $table) {
                $table->date('accreditation_start')
                    ->nullable()
                    ->after('birth_date')
                    ->comment('Doctor Accreditation Start');
                $table->date('accreditation_end')
                    ->nullable()
                    ->after('accreditation_start')
                    ->comment('Doctor Accreditation End');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'doctors',
            function (Blueprint $table) {
                $table->dropColumn(
                    [
                        'accreditation_start',
                        'accreditation_end',
                    ]
                );
            }
        );

    }
}
