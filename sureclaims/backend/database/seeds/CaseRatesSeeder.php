<?php

use App\Model\Repositories\Contracts\CaseRateRepository;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CaseRatesSeeder extends Seeder
{
    /**
     * @var CaseRateRepository
     */
    private $caseRate;

    /**
     * @var array
     */
    private $data;

    /**
     * CaseRatesTableSeeder constructor.
     * @param CaseRateRepository $caseRate
     */
    public function __construct( CaseRateRepository $caseRate )
    {
        $this->caseRate = $caseRate;
        $json = file_get_contents( dirname( __DIR__ ) . '/data/caserates-01-2018.json' );
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

            $this->caseRate->updateOrCreate( [
                'code' => array_get( $data, 'acr_groupid' ),
                'item_code' => array_get( $data, 'code' ),
                'hospital_type' => null
            ], [
                'description' => array_get( $data, 'group' ),
                'item_description' => array_get( $data, 'description' ),
                'hci_fee' => array_get( $data, 'hf' ),
                'prof_fee' => array_get( $data, 'pf' ),
                'allow_second' => array_get( $data, 'is_allowed_second' ),
                'secondary_hci_fee' => array_get( $data, 'shf' ),
                'secondary_prof_fee' => array_get( $data, 'spf' ),
                'effectivity_date' => Carbon::createFromFormat( 'd/m/Y', array_get( $data, 'date_from' ) )->toDateString(),
                'case_type' => array_get( $data, 'case_type' ) == 'm' ? 'icd' : 'rvs',
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
