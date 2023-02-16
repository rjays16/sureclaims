<?php

use App\Model\Repositories\Contracts\SecondCaseRateRepository;
use Illuminate\Database\Seeder;

class SecondCaseRatesSeeder extends Seeder
{
    /**
     * @var SecondCaseRateRepository
     */
    private $secondCaseRate;

    /**
     * @var array
     */
    private $data;

    /**
     * CaseRatesTableSeeder constructor.
     * @param SecondCaseRateRepository $caseRate
     */
    public function __construct( SecondCaseRateRepository $caseRate )
    {
        $this->secondCaseRate = $caseRate;
        $json = file_get_contents( dirname( __DIR__ ) . '/data/caserate-second-01-2018.json' );
        $this->data = json_decode( $json, true );
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $totalCount = count( $this->data );
        $savedCount = 0;

        foreach ( $this->data as $data ) {

            $this->secondCaseRate->updateOrCreate( [
                'code' => array_get( $data, 'package' ),
//                'description' => array_get( $data, 'group' ),
                'item_code' => array_get( $data, 'code' ),
                'item_description' => array_get( $data, 'description' ),
                'hci_fee' => array_get( $data, 'hf' ),
                'prof_fee' => array_get( $data, 'pf' ),
//                'effectivity_date' => Carbon::createFromFormat( 'd/m/Y', array_get($data, 'date_from'))->toDateString(),
                'effectivity_date' => \Carbon\Carbon::now()->format( 'Y-m-d' ),
                'case_type' => array_get( $data, 'case_type' ) == 'p' ? 'rvs' : 'icd',
            ] );

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
