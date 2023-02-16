<?php

/**
 * PersonQueryTest.php
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
 * Class PersonQueryTest
 *
 * @package Tests\Feature\GraphQL
 */
class PersonQueryTest extends GraphQLTestCase
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
    }

    /**
     * @test
     */
    public function shouldReturnPersonData()
    {
        $person = factory(Person::class)->create();
        $response = $this->executeQuery($this->query(), ['id' => $person->id]);
        $response->assertJsonStructure([
            'data' => [
                'person' => [
                    'id',
                    'lastName',
                    'firstName',
                    'middleName',
                    'member',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function shouldReturnPersonDataWithMemberData()
    {
        /** @var Person $person */
        /** @var Member $member */
        $person = factory(Person::class)->create();
        $member = factory(Member::class)->make();
        $member->person()->associate($person);
        $member->relation = 'C';
        $member->save();
        $response = $this->executeQuery($this->query(), ['id' => $person->id]);
        $response->assertJsonStructure([
            'data' => [
                'person' => [
                    'id',
                    'lastName',
                    'firstName',
                    'middleName',
                    'member' => [
                        'pin'
                    ],
                ],
            ],
        ]);
    }

    /**
     * @param array $args
     * @return string
     */
    private function query() : string
    {

        return <<<'GRAPHQL'
query Person($id: ID) {
  person(id: $id) {
    ...PersonData
    __typename
  }
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
