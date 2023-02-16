<?php

/**
 * DeleteDoctorMutationTest.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Feature\GraphQL\Mutations;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\Feature\GraphQL\GraphQLTestCase;

/**
 * Class DeleteDoctorMutationTest
 * @package Tests\Feature\GraphQL\Mutations
 */
class DeleteDoctorMutationTest extends GraphQLTestCase
{

    /**
     * @test
     */
    public function shouldDeleteDoctor()
    {
        $doctor = $this->provideDoctor();
        $doctor->save();
        $data = $this->doctors()->present($doctor);

        $response = $this->executeQuery($this->query(), [
            'id' => $doctor->id,
        ]);

        $result = $response->json();

        $this->assertEquals(
            $doctor->id,
            array_get($result, 'data.deleteDoctor.id'),
            'ID in deleteDoctor result not equal to original doctor ID'
        );

        // Make sure that doctor in result is the same doctor we are deleting
        $this->assertEquals(
            array_only($data, ['lastName', 'firstName', 'middleName']),
            array_only(array_get($result, 'data.deleteDoctor'), ['lastName', 'firstName', 'middleName'])
        );

        // Check if the doctor row was actually deleted in database
        $this->expectException(ModelNotFoundException::class);
        $this->doctors()->find(array_get($data, 'id'));
    }

    /**
     *
     */
    protected function query()
    {
        return <<<'GRAPHQL'
mutation DeleteDoctor(
  $id: ID!
) {
  deleteDoctor(
    id: $id
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
}

GRAPHQL;
    }

}
