<?php

/**
 * TransmittalsQueryTest.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Feature\GraphQL\Queries;

use App\Model\Entities\Claim;
use App\Model\Entities\Transmittal;
use Tests\Feature\GraphQL\GraphQLTestCase;

/**
 * Class TransmittalsQueryTest
 *
 * @package Tests\Feature\GraphQL\Queries
 */
class TransmittalsQueryTest extends GraphQLTestCase
{
    /**
     * @test
     */
    public function shouldReturnCorrectSizeAndStructure()
    {
        $size = 5;
        $this->provideTransmittals($size)->each(function(Transmittal $transmittal) {
            $transmittal->save();
        });

        $response = $this->executeQuery($this->query());
        $response->assertJsonStructure([
            'data' => [
                'transmittals' => [
                    'transmittals' => [
                        '*' => [
                            'id',
                        ],
                    ],
                ],
            ],
        ]);

        $result = $response->json();
        $this->assertEmpty(array_get($result, 'errors'));

        $data = array_get($result, 'data.transmittals.transmittals');
        $this->assertSameSize(range(1, $size), $data);
    }

    /**
     * @test
     */
    public function shouldBeAbleToLoadExistingClaimsData()
    {
        $size = 5;
        $that = $this;
        $this->provideTransmittals($size)->each(function(Transmittal $transmittal) use ($that) {
            $transmittal->save();
            $claim = new Claim();
            $claim->transmittal()->associate($transmittal)->save();
        });

        $response = $this->executeQuery($this->query());
        // $data = array_get($response->json(), 'data.transmittals');

        $response->assertJsonStructure([
            'data' => [
                'transmittals' => [
                    'transmittals' => [
                        '*' => [
                            'id',
                            'claims' => [
                                '*' => [
                                    'id',
                                    'claimNumber',
                                ]
                            ],
                        ],
                    ],
                ],
            ],
        ]);


    }

    /**
     *
     */
    private function query() : string
    {
        return <<<'GRAPHQL'
query Transmittals($page: Int, $pageSize: Int) {
  transmittals(page: $page, pageSize: $pageSize) {
    transmittals {
      ...TransmittalData
      claims {
        ...ClaimData
      }
      __typename
    }
    pageInfo {
      ...Pagination
      __typename
    }
    __typename
  }
}

fragment Pagination on PageInfo {
  currentPage
  pageSize
  lastPage
  hasMorePages
  total
  __typename
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

fragment ClaimData on Claim {
  id
  claimNumber
  patientId
  admissionDate
  dischargeDate
  status
  data
  xml
  isValid
  validationErrors
}
GRAPHQL;

    }
}
