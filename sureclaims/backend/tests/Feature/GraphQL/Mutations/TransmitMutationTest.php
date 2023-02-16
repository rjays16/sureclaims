<?php

namespace Tests\Feature\GraphQL\Mutations;

use App\Model\Entities\Claim;
use Tests\Feature\GraphQL\GraphQLTestCase;

class TransmitMutationTest extends GraphQLTestCase
{

    /**
     * @test
     */
    public function shouldHaveAtLeastOneTest()
    {
        $this->assertTrue(true);
    }

    /**
     *
     */
    protected function query()
    {
        return <<<'GRAPHQL'
mutation Transmit(
  $id: ID!
) {
  transmit(
    id: $id
  ) {
    ...TransmittalData
  }
}

fragment TransmittalData on Transmittal {
  id
  transmittalNo
  transmittalControlCode
  transmitDate
  transmitTime
  receiptTicketNo
  notes
  transmitErrors
  createdAt
  updatedAt
  __typename
}
GRAPHQL;
    }

}
