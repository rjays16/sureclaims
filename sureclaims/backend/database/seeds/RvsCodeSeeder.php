<?php

use App\Model\Repositories\Contracts\RvsCodeRepository;
use Illuminate\Database\Seeder;

class RvsCodeSeeder extends Seeder
{
    /**
     * @var RvsCodeRepository
     */
    private $rvsCode;

    /**
     * RvsCodeSeeder constructor.
     * @param RvsCodeRepository $rvsCode
     */
    public function __construct( RvsCodeRepository $rvsCode )
    {
        $this->rvsCode = $rvsCode;
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents( dirname( __DIR__ ) . '/data/rvuPhic.json' );
        $codes = json_decode( $json, true );
        $totalCount = count( $codes );
        $savedCount = 0;

        foreach ($codes as $data) {

            $hasCode = !empty(trim(array_get( $data, 'code' )));
            if ( !$hasCode ) {
                continue;
            }

            $this->rvsCode->updateOrCreate( [
                'code' => trim(array_get( $data, 'code' ))
            ], [
                'description' => trim(array_get( $data, 'description' )),
                'with_laterality' => (int) !!trim(array_get( $data, 'with_laterality' ))
            ]);

            ++$savedCount;

        }

        $errorCount = $totalCount - $savedCount;
        echo str_repeat( '-', 60 ) . "\n";
        echo "Actual items count: {$totalCount}\n";
        echo "Saved count: {$savedCount}\n";
        echo "Error count: {$errorCount}\n";
        echo "Done seeding ...\n";
        echo str_repeat( '-', 60 ) . "\n";
    }
}
