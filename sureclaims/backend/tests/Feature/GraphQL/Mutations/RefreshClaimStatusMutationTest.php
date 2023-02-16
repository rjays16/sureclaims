<?php

namespace Tests\Feature\GraphQL\Mutations;

use App\Model\Entities\Claim;
use Tests\Feature\GraphQL\GraphQLTestCase;

/**
 * Class RefreshClaimStatusMutationTest
 * @package Tests\Feature\GraphQL\Mutations
 */
class RefreshClaimStatusMutationTest extends GraphQLTestCase
{
    /**
     * @var Claim
     */
    private $claim;

    public function setUp(): void
    {
        parent::setUp();
        $person = $this->providePerson();
        $person->save();

        $this->claim = new Claim();
        $this->claim->patient_id = $person->id;
        $this->claim->lhio_series_no = '120723190000119';
        $this->claim->save();
    }

    /**
     * Update test. Use mock.
     * @return void
     */
    public function shouldRefreshClaimStatus()
    {
        $response = $this->executeQuery( $this->query(), [
            'ids' => [ $this->claim->id ]
        ] );

        $response->assertJsonStructure( [
            'data' => [
                'refreshClaimStatus' => [
                    '*' => [
                        'id',
                        'status',
                    ],
                ]
            ]
        ] );
    }

    private function query(): string
    {
        return <<<'GRAPHQL'
mutation refreshClaimStatus($ids: [ID]!) {
  refreshClaimStatus(ids: $ids) {
    id
    status
  }
}

GRAPHQL;
    }

}
