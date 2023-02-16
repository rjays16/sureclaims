<?php

/**
 * UpdateDoctorMutationTest.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Feature\GraphQL\Mutations;

use Tests\Feature\GraphQL\GraphQLTestCase;

/**
 * Class UpdateDoctorMutationTest
 * @package Tests\Feature\GraphQL\Mutations
 */
class UpdateDoctorMutationTest extends GraphQLTestCase
{
    /**
     * @test
     */
    public function shouldUpdateDoctorData()
    {
        $doctor = $this->provideDoctor();
        $doctor->save();

        $data = $this->doctors()->present($doctor);

        $data['lastName'] = $this->faker->shuffleString($data['lastName']);
        $data['firstName'] = $this->faker->shuffleString($data['firstName']);
        $data['middleName'] = $this->faker->shuffleString($data['middleName']);

        $response = $this->executeQuery($this->query(), [
            'id' => $doctor->id,
            'lastName' => $data['lastName'],
            'firstName' => $data['firstName'],
            'middleName' => $data['middleName'],
        ]);

        $result = array_get($response->json(), 'data.updateDoctor');

        $this->assertNotEmpty($result);

        $this->assertEquals($doctor->id, $result['id'], 'Doctor ID in updated result not equal to original doctor ID');

        // Sanity check to make sure that
        $this->assertNotEquals(
            array_only($data, ['lastName', 'firstName', 'middleName']),
            array_only($doctor->toArray(), ['last_name', 'first_name', 'middle_name'])
        );

        // Check if the database has actually been updated
        $updated = $this->doctors()->find($doctor->id);
        $this->assertEquals(
            array_only($data, ['lastName', 'firstName', 'middleName']),
            array_only($updated, ['lastName', 'firstName', 'middleName'])
        );
    }

    /**
     *
     */
    protected function query()
    {
        return <<<'GRAPHQL'
mutation UpdateDoctor(
  $id: ID!
  $pan: String
  $tin: String
  $lastName: String
  $firstName: String
  $middleName: String
  $birthDate: String
  $suffix: String
) {
  updateDoctor(
    id: $id
    pan: $pan
    tin: $tin
    lastName: $lastName
    firstName: $firstName
    middleName: $middleName
    birthDate: $birthDate
    suffix: $suffix
  ) {
    ...DoctorData
  }
}

fragment DoctorData on Doctor {
  id
  pan
  tin
  lastName
  firstName
  middleName
  birthDate
  suffix
  createdAt
  updatedAt
}
GRAPHQL;
    }
}
