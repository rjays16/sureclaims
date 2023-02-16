<?php

/**
 * DoctorsQueryTest.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Feature\GraphQL\Queries;

use App\Model\Entities\Doctor;
use Tests\Feature\GraphQL\GraphQLTestCase;


/**
 * Class DoctorsQueryTest
 *
 * @package Tests\Feature\GraphQL
 */
class DoctorsQueryTest extends GraphQLTestCase
{
    /**
     * @test
     */
    public function shouldReturnValidStructure()
    {
        $this->provideDoctors(10)->each(function(Doctor $doctor) {
            $doctor->save();
        });
        $response = $this->executeQuery($this->query(), [
            'name' => '',
            'page' => 1,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'doctors' => [
                    'doctors' => [
                        '*' => [
                            'id',
                            'lastName',
                            'firstName',
                        ],
                    ],
                    'pageInfo'
                ]
            ]
        ]);
    }

    /**
     * @param array $args
     * @return string
     */
    private function query() : string
    {
        return <<<'GRAPHQL'
query Doctors($name: String, $page: Int, $pageSize: Int) {
  doctors(name: $name, page: $page, pageSize: $pageSize) {
    doctors {
      ...DoctorData
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

fragment DoctorData on Doctor {
  id
  pan
  tin
  lastName
  firstName
  middleName
  suffix
  birthDate
  __typename
}
GRAPHQL;
    }
}
