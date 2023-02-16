<?php

/**
 * CreatePersonMutationTest.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Feature\GraphQL\Mutations;

use App\GraphQL\Type\Member\MemberInputType;
use App\GraphQL\Type\Person\PersonInputType;
use App\Model\Entities\Member;
use App\Model\Entities\Person;
use App\Model\Repositories\Contracts\MemberRepository;
use App\Model\Repositories\Contracts\PersonRepository;
use Tests\Feature\GraphQL\GraphQLTestCase;

/**
 * Class CreatePersonMutationTest
 * @package Tests\Feature\GraphQL\Mutations
 */
class CreatePersonMutationTest extends GraphQLTestCase
{
    /** @var PersonRepository */
    private $persons;

    /** @var MemberRepository */
    private $members;

    /**
     *
     */
    public function setUp() : void
    {
        parent::setUp();
        $this->persons = \App::make(PersonRepository::class);
        $this->members = \App::make(MemberRepository::class);
    }

    /**
     * @atest
     */
    public function shouldCreatePerson()
    {
        $person = $this->providePerson();
        $data = $this->transformPerson($person);
        $response = $this->executeQuery($this->query(), [
            'person' => $data,
            'member' => null
        ]);

        $response->assertJsonStructure([
            'data' => [
                'createPerson' => [
                    'id',
                    'lastName',
                    'firstName',
                    'middleName',
                    'member'
                ],
            ],
        ]);

        $member = array_get($response->json(), 'data.createPerson.member');
        $this->assertEmpty($member);
    }

    /**
     * @test
     */
    public function shouldCreatePersonWithMemberData()
    {
        $person = $this->providePerson();
        $personData = $this->transformPerson($person);
        $member = $this->provideMember();
        $memberData = $this->transformMember($member);

        $response = $this->executeQuery($this->query(), [
            'id' => $person->id,
            'person' => $personData,
            'member' => $memberData,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'createPerson' => [
                    'id',
                    'lastName',
                    'firstName',
                    'middleName',
                    'member' => [
                        'pin',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function shouldCreatePersonWithMemberDataAndPrincipal()
    {
        $principal = $this->providePerson();
        $principal->save();
        $person = $this->providePerson();
        $personData = $this->transformPerson($person);
        $member = $this->provideMember();
        $memberData = $this->transformMember($member);
        $memberData['relation'] = 'C'; // Child
        $memberData['principalId'] = $principal->id;

        $response = $this->executeQuery($this->query(), [
            'id' => $person->id,
            'person' => $personData,
            'member' => $memberData,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'createPerson' => [
                    'id',
                    'lastName',
                    'firstName',
                    'middleName',
                    'member' => [
                        'principal' => [
                            'lastName',
                            'firstName',
                            'middleName',
                        ]
                    ]
                ],
            ],
        ]);
    }

    /**
     *
     */
    protected function query()
    {
        return <<<'GRAPHQL'
mutation CreatePerson(
  $person: PersonInput!
  $member: MemberInput
) {
  createPerson(
    person: $person
    member: $member
  ) {
    ...PersonData
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
  }
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
  }
}

fragment PrincipalData on Person {
  id
  lastName
  firstName
  middleName
  birthDate
  suffix
  sex
}
GRAPHQL;
    }

    /**
     * @param Person $person
     */
    private function transformPerson(Person $person)
    {
        $fieldSets = ['person' => implode(',', array_keys((new PersonInputType())->fields()))];
        $this->persons->parsePresenterFieldSets($fieldSets);
        return $this->persons->present($person);
    }

    /**
     * @param Member $person
     */
    private function transformMember(Member $member)
    {
        $fieldSets = ['member' => implode(',', array_keys((new MemberInputType())->fields()))];
        $this->members->parsePresenterFieldSets($fieldSets);
        return $this->members->present($member);
    }

    /**
     *
     */
    private function providePerson() : Person
    {
        return factory(Person::class)->make();
    }

    /**
     *
     */
    private function provideMember() : Member
    {
        return factory(Member::class)->make();
    }
}
