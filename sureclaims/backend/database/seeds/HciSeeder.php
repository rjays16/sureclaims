<?php


namespace {

    use Illuminate\Database\Seeder;
    use App\Model\Entities\Hci;

    class HciSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            factory(Hci::class, 10)->create();
        }
    }
}