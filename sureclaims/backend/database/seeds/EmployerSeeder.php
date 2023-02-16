<?php

use Illuminate\Database\Seeder;

class EmployerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory( \App\Model\Entities\Employer::class, 5 )->create();
    }
}
