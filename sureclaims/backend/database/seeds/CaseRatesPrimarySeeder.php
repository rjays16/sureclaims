<?php

use App\Model\Entities\CaseRate;
use App\Model\Repositories\Contracts\CaseRateRepository;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CaseRatesPrimarySeeder extends Seeder
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
        $json = file_get_contents( dirname( __DIR__ ) . '/data/caserate-primary.json' );
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

        $error = [];
        foreach ( $this->data as $data ) {

            $date = empty($data['date_from']) ?
                Carbon::now()->toDateString() :
                Carbon::createFromFormat( 'd/m/Y', array_get( $data, 'date_from' ) )->toDateString();

            $this->caseRate->updateOrCreate( [
                'code' => array_get( $data, 'code' ),
                'item_code' => array_get( $data, 'item_code' ),
                'hospital_type' => 'PRIMARY'
            ], [
                'description' => array_get( $data, 'description' ),
                'item_description' => array_get( $data, 'item_description' ),
                'hci_fee' => array_get( $data, 'hci_fee' ),
                'prof_fee' => array_get( $data, 'prof_fee' ),
                'effectivity_date' => $date,
                'case_type' => array_get( $data, 'case_type' ) == 'm' ? 'icd' : 'rvs',
            ] );

            ++$savedCount;
        }

        $errorCount = $totalCount - $savedCount;
        echo str_repeat( '-', 60 ) . "\n";
        echo "Actual items count: {$totalCount}\n";
        echo "Error count: {$errorCount}\n";
        echo "Added count: {$savedCount}\n";
        echo "Done seeding ...\n";
        echo str_repeat( '-', 60 ) . "\n";
    }
}
