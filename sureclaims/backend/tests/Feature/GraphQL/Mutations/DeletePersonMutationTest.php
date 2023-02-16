<?php

/**
 * DeletePersonMutationTest.php
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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\Feature\GraphQL\GraphQLTestCase;

/**
 * Class DeletePersonMutationTest
 * @package Tests\Feature\GraphQL\Mutations
 */
class DeletePersonMutationTest extends GraphQLTestCase
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
    public function shouldDeletePerson()
    {
        $person = $this->providePerson();
        $data = $this->transformPerson($person);

        // Sanity check
        $this->assertNotEmpty(array_get($data, 'member'));

        $response = $this->executeQuery($this->query(), [
            'id' => $person->id,
        ]);

        $result = $response->json();

        $this->assertEquals(
            $person->id,
            array_get($result, 'data.deletePerson.id'),
            'ID in deletePerson result not equal to original person ID'
        );

        // Make sure that person in result is the same person we are deleting
        $this->assertEquals(
            array_only($data, ['lastName', 'firstName', 'middleName']),
            array_only(array_get($result, 'data.deletePerson'), ['lastName', 'firstName', 'middleName'])
        );

        // Check if the person row was actually deleted in database
        $this->expectException(ModelNotFoundException::class);
        $this->persons->find(array_get($data, 'id'));
    }

    /**
     * @test
     */
    public function shouldActuallyUseSoftDeletes()
    {
        $person = $this->providePerson();
        $data = $this->transformPerson($person);

        // Sanity check
        $this->members->find(array_get($data, 'member.id'));

        $this->executeQuery($this->query(), [
            'id' => $person->id,
        ]);

        // Check if the member row was actually deleted in the database
        $person = Person::withTrashed()->where('id', $person->id)->first();
        $this->assertNotEmpty($person);

        $deleted = $this->persons->present($person);

        // Make sure that soft deleted person is the same as the deleted person
        $this->assertEquals(
            array_only($data, ['lastName', 'firstName', 'middleName']),
            array_only($deleted, ['lastName', 'firstName', 'middleName'])
        );
    }

    /**
     * @test
     */
    public function shouldDeleteMemberDataAsWell()
    {
        $person = $this->providePerson();
        $data = $this->transformPerson($person);

        // Sanity check
         $this->members->find(array_get($data, 'member.id'));

        $this->executeQuery($this->query(), [
            'id' => $person->id,
        ]);

        // Check if the member row was actually deleted in the database
        $this->expectException(ModelNotFoundException::class);
        $this->members->find(array_get($data, 'member.id'));
    }

    /**
     *
     */
    protected function query()
    {
        return <<<'GRAPHQL'
mutation DeletePerson(
  $id: ID!
) {
  deletePerson(
    id: $id
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
     * @return Person
     */
    private function providePerson() : Person
    {
        /**
         * @var Person $person
         * @var Member $member
         * @var Person $principal
         */
        $person = factory(Person::class)->create();
        $member = factory(Member::class)->make();
        $member->person()->associate($person)->save();
        return $person;
    }

    /**
     * @param Person $person
     */
    private function transformPerson(Person $person) : array
    {
        $this->persons->parsePresenterIncludes(['member']);
        return $this->persons->present($person);
    }
}
