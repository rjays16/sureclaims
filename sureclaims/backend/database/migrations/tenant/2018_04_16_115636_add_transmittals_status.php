<?php

/**
 * 2018_04_16_115636_add_transmittal_status.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace {

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    /**
     * Class AddTransmittalsStatus
     */
    class AddTransmittalsStatus extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('transmittals', function(Blueprint $table) {
                $table->string('status', 20)
                    ->nullable()
                    ->after('auto_transmit');
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('transmittals', function(Blueprint $table) {
                $table->removeColumn('status');
            });
        }
    }
}