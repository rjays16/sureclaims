<?php

/**
 * PersonsPageQueryTest.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Feature\GraphQL\Queries;

use App\Model\Entities\Member;
use App\Model\Entities\Person;
use App\Model\Repositories\Contracts\PersonRepository;
use Tests\Feature\GraphQL\GraphQLTestCase;


/**
 * Class PersonsPageQueryTest
 *
 * @package Tests\Feature\GraphQL
 */
class PersonsPageQueryTest extends GraphQLTestCase
{
    /** @var PersonRepository */
    private $persons;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->persons = \App::make(PersonRepository::class);

        factory(Member::class, 10)->make()->each(function (Member $member) {
            $member->person()
                ->associate(factory(Person::class)->create())
                ->save();

            if (rand(1, 100) > 50) {
                $member->principal()
                    ->associate(factory(Person::class)->create())
                    ->save();
            }
        });
    }

    /**
     * @test
     */
    public function shouldReturnValidStructure()
    {
        $response = $this->executeQuery($this->query(), [
            'name' => '',
            'page' => 1,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'personsPage' => [
                    'persons' => [
                        '*' => [
                            'id',
                            'member',
                        ],
                    ],
                    'pageInfo'
                ]
            ]
        ]);

        foreach(array_get($response->json(), 'data.personsPage.persons') as $person) {
            if (!empty($person['member'])) {
                $this->assertArrayHasKey('principal', $person['member']);

                if (!empty($person['member']['principalId'])) {
                    $this->assertNotEmpty(
                        $person['member']['principal'],
                            'Principal data is empty even though principalId has a value'
                    );
                }
            } else {
                // Check if the person actually does not have a corresponding member data
                $p = $this->persons->skipPresenter()->find($person['id']);
                $this->assertEmpty($p->member,
                    'Query did not contain member data even though this person has a member record'
                );
            }
        }
    }

    /**
     * @test
     */
    public function shouldReturnErrorOnInvalidVariable()
    {
        $response = $this->executeQuery($this->query(), [
            'pageSize' => 'Not A Number',
        ]);

        $response->assertJsonStructure([
            'errors' => [
                '*' => [
                    'message',
                ],
            ]
        ]);
    }

    /**
     * @test
     * @dataProvider providePageSizes
     */
    public function shouldReturnCountBaseOnPageSize($pageSize)
    {
        $response = $this->executeQuery($this->query(), [
            'pageSize' => $pageSize,
        ]);

        $persons = array_get($response->json(), 'data.personsPage.persons');
        $this->assertSameSize(range(1, $pageSize), $persons);
    }

    /**
     * @test
     */
    public function shouldReturnLimitedFields()
    {
        $response = $this->executeQuery($this->limitedQuery(), [
            'pageSize' => 5,
        ]);

        foreach(array_get($response->json(), 'data.personsPage.persons') as $person) {
            $this->assertArrayNotHasKey('middleName', $person);
            $this->assertArrayNotHasKey('member', $person);
        }
    }

    /**
     * @return array
     */
    public function providePageSizes() : array
    {
        return array_map(function ($n) { return [$n]; }, range(1, 5));
    }

    /**
     * @return string
     */
    private function limitedQuery() : string
    {
        return <<<'GRAPHQL'
query PersonsPage($name: String, $page: Int, $pageSize: Int) {
  personsPage(name: $name, page: $page, pageSize: $pageSize) {
    persons {
      ...PersonData
      __typename
    }
    __typename
  }
}

fragment PersonData on Person {
  id
  lastName
  firstName
  __typename
}
GRAPHQL;
    }

    /**
     * @param array $args
     * @return string
     */
    private function query() : string
    {

        return <<<'GRAPHQL'
query PersonsPage($name: String, $page: Int, $pageSize: Int) {
  personsPage(name: $name, page: $page, pageSize: $pageSize) {
    persons {
      ...PersonData
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

fragment PersonData on Person {
  id
  lastName
  firstName
  middleName
  birthDate
  suffix
  sex
  mailingAddress
  emailAddress
  zipCode
  landLineNo
  mobileNo
  createdAt
  updatedAt
  member {
    ...MemberData
    __typename
  }
  __typename
}

fragment MemberData on Member {
  id
  pin
  membershipType
  pen
  employerName
  createdAt
  updatedAt
  relation
  principalId
  principal {
    ...PrincipalData
    __typename
  }
  __typename
}

fragment PrincipalData on Person {
  id
  lastName
  firstName
  middleName
  birthDate
  suffix
  sex
  __typename
}
GRAPHQL;
    }
}
