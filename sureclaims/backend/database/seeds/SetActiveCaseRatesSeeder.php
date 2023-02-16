<?php

use Illuminate\Database\Seeder;

class SetActiveCaseRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('case_rates')
            ->where('is_active', '=', 0)
            ->update([
                'is_active' => 1
            ]);
    }
}
