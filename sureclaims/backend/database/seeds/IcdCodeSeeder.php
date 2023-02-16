<?php

/**
 * IcdCodeSeeder.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace {

    use App\Model\Entities\IcdCode;
    use App\Model\Repositories\Contracts\IcdCodeRepository;
    use Illuminate\Database\Seeder;

    /**
     * Class IcdCodeSeeder
     */
    class IcdCodeSeeder extends Seeder
    {


        /**
         * @var IcdCodeRepository
         */
        private $icd;

        /**
         * IcdCodeSeeder constructor.
         * @param IcdCodeRepository $icd
         */
        public function __construct( IcdCodeRepository $icd )
        {
            $this->icd = $icd;
        }

        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            $icd = json_decode( file_get_contents( dirname( __DIR__ ) . '/data/icd-codes.json' ), true );

            $totalCount = count( $icd );
            $savedCount = 0;

            $error = [];
            foreach ( $icd as $data ) {

                $this->icd->updateOrCreate(
                    [ 'code' => trim(array_get( $data, 'code' )) ],
                    [ 'description' => trim(array_get( $data, 'description' )) ]
                );

                ++$savedCount;
            }
            
            // file_put_contents(dirname( __DIR__ ) . '/data/not-saved-icd.json', json_encode($error));

            $errorCount = $totalCount - $savedCount;
            echo str_repeat( '-', 60 ) . "\n";
            echo "Actual items count: {$totalCount}\n";
            echo "Error count: {$errorCount}\n";
            echo "Added count: {$savedCount}\n";
            echo "Done seeding ...\n";
            echo str_repeat( '-', 60 ) . "\n";
        }
    }

}
