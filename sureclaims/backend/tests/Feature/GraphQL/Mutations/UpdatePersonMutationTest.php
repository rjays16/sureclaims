<?php

/**
 * UpdatePersonMutationTest.php
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
 * Class UpdatePersonMutationTest
 * @package Tests\Feature\GraphQL\Mutations
 */
class UpdatePersonMutationTest extends GraphQLTestCase
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
     * @test
     */
    public function shouldFuckingUpdatePersonData()
    {
        $person = $this->providePerson(false);
        $data = $this->transformPerson($person);

        $data['lastName'] = $this->faker->shuffleString($data['lastName']);
        $data['firstName'] = $this->faker->shuffleString($data['firstName']);
        $data['middleName'] = $this->faker->shuffleString($data['middleName']);

        $response = $this->executeQuery($this->query(), [
            'id' => $person->id,
            'person' => $data,
            'member' => null
        ]);

        $result = array_get($response->json(), 'data.updatePerson');
        $this->assertEquals($person->id, $result['id'], 'Person ID in updated result not equal to original person ID');

        // Sanity check to make sure that
        $this->assertNotEquals(
            array_only($data, ['lastName', 'firstName', 'middleName']),
            array_only($person->toArray(), ['last_name', 'first_name', 'middle_name'])
        );

        // Check if the database has actually been updated
        $updated = $this->persons->find($person['id']);
        $this->assertEquals(
            array_only($data, ['lastName', 'firstName', 'middleName']),
            array_only($updated, ['lastName', 'firstName', 'middleName'])
        );
    }

    /**
     * @test
     */
    public function shouldUpdateMemberDataWithPrincipal()
    {
        $person = $this->providePerson(true);
        $personData = $this->transformPerson($person);

        // Sanity check
        $this->assertNotEmpty($person->member);

        $memberData = $this->transformMember($person->member);

        $principal = $this->providePerson(false);
        $memberData['principalId'] = $principal->id;
        $memberData['relation'] = 'C'; // Child

        $response = $this->executeQuery($this->query(), [
            'id' => $person->id,
            'person' => $personData,
            'member' => $memberData
        ]);

        // Check structure
        $response->assertJsonStructure([
            'data' => [
                'updatePerson' => [
                    'id',
                    'member' => [
                        'id',
                        'principal' => [
                            'id',
                            'lastName',
                            'firstName',
                            'middleName'
                        ]
                    ],
                ],
            ],
        ]);

        // Check if return data is correct
        $principalArray = array_get($response->json(), 'data.updatePerson.member.principal');
        $this->assertEquals(
            array_values(array_only($principal->toArray(), ['last_name', 'first_name', 'middle_name'])),
            array_values(array_only($principalArray, ['lastName', 'firstName', 'middleName']))
        );

        // Check data in database
        // Check if the database has actually been updated
        $updated = $this->persons->find($principal->id);
        $this->assertEquals(
            array_only($updated, ['lastName', 'firstName', 'middleName']),
            array_only($principalArray, ['lastName', 'firstName', 'middleName'])
        );
    }

    /**
     * @test
     */
    public function shouldBeAbleToSetSelfAsPrincipalMember()
    {
        $person = $this->providePerson(true);
        $personData = $this->transformPerson($person);
        $memberData = $this->transformMember($person->member);

        $memberData['relation'] = 'M'; // Member!
        $this->executeQuery($this->query(), [
            'id' => $person->id,
            'person' => $personData,
            'member' => $memberData
        ]);

        // Check data in database
        // Check if the database has actually been updated
        $member = $this->members->skipPresenter()->find($person->member->id);

        $this->assertNotEmpty($member->principal, 'Principal member was not set');

        $person = $this->persons->find($person->id);
        $principal = $this->persons->find($member->principal_id);
        $this->assertEquals(
            array_only($person, ['lastName', 'firstName', 'middleName']),
            array_only($principal, ['lastName', 'firstName', 'middleName'])
        );
    }

    /**
     *
     */
    protected function query()
    {
        return <<<'GRAPHQL'
mutation UpdatePerson(
  $id: ID!
  $person: PersonInput!
  $member: MemberInput
) {
  updatePerson(
    id: $id
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
     * @param bool $withMemberData
     * @param bool $withPrincipal
     */
    private function providePerson($withMemberData = false)
    {
        /**
         * @var Person $person
         * @var Member $member
         * @var Person $principal
         */
        $person = factory(Person::class)->create();

        if ($withMemberData) {
            $member = factory(Member::class)->create();
            $member->person()->associate($person)->save();
        }

        return $person;
    }
}
