<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Model\Entities\SecondCaseRate;
use Tests\Feature\GraphQL\GraphQLTestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SecondCaseRatesQueryTest extends GraphQLTestCase
{
    /**
     * A basic test example.
     *
     * @test
     */
    public function should_return_default_list_of_case_rates()
    {
        $this->provideSecondCaseRates( 5 )->each( function ( SecondCaseRate $secondCaseRate ) {
            $secondCaseRate->save();
        } );

        $response = $this->executeQuery( $this->query() );
        $result = $response->json();
        /**
         * Assert results is not empty
         */
        $this->assertNotEmpty( $result );

        /**
         * assert response errors is empty
         */
        $errors = array_get( $result, 'errors', [] );
        if ( !empty( $errors ) ) {
            ~s( $errors );
        }
        $this->assertEmpty( $errors, 'Second Case rates query has errors!' );

        /**
         * assert case rates is not empty
         */
        $data = array_get( $result, 'data.secondCaseRates', [] );
        $this->assertNotEmpty( $data );

        /**
         * assert response data pattern/schema
         */
        try {
            $response->assertJsonStructure( $this->jsonSchema() );
        } catch ( \Exception $e ) {
            ~s( $result );
            throw $e;
        }
    }

    private function jsonSchema()
    {
        return [
            'data' => [
                'secondCaseRates' => [
                    '*' => [
                        'id',
                        'code',
                        'description',
                        'itemCode',
                        'itemDescription',
                        'hciFee',
                        'profFee',
                        'amount',
                        'effectivityDate',
                        'caseType',
                        'isIcd',
                        'isRvs',
                        'createdAt',
                        'updatedAt',
                    ],
                ]
            ]
        ];
    }

    private function query()
    {
        return <<<'GRAPHQL'
query SecondCaseRatesQuery {
  secondCaseRates {
    id
    code
    description
    itemCode
    itemDescription
    hciFee
    profFee
    amount
    effectivityDate
    caseType
    isIcd
    isRvs
    createdAt
    updatedAt
  }
}
GRAPHQL;

    }
}
