<?php

/**
 * CreateDoctorMutationTest.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Feature\GraphQL\Mutations;

use Tests\Feature\GraphQL\GraphQLTestCase;

/**
 * Class CreateDoctorMutationTest
 * @package Tests\Feature\GraphQL\Mutations
 */
class CreateDoctorMutationTest extends GraphQLTestCase
{

    /**
     * @test
     */
    public function shouldCreateDoctor()
    {
        $doctor = $this->provideDoctor();
        $data = $this->doctors()->present($doctor);
        $response = $this->executeQuery($this->query(), array_only($data, [
            'pan',
            'tin',
            'lastName',
            'firstName',
            'middleName',
            'suffix',
            'birthDate'
        ]));

        $result = $response->json();
        $this->assertEmpty(array_get($result, 'errors'));

        $response->assertJsonStructure([
            'data' => [
                'createDoctor' => [
                    'id',
                    'tin',
                    'pan',
                    'lastName',
                    'firstName',
                    'middleName',
                    'suffix',
                    'birthDate'
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
mutation CreateDoctor(
  $pan: String
  $tin: String
  $lastName: String
  $firstName: String
  $middleName: String
  $birthDate: String
  $suffix: String
) {
  createDoctor(
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
