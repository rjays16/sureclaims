<?php

namespace Tests\Feature\GraphQL\Mutations;

use App;
use App\Lib\Soap\EClaimsServiceHIE;
use App\Lib\Soap\EclaimsServiceInterface;
use Carbon\Carbon;
use Tests\Feature\GraphQL\GraphQLTestCase;

class GetDoctorPANMutationTest extends GraphQLTestCase
{
    /**
     * @test
     */
    public function shouldHandleSuccessfulServiceCallResult()
    {
        $mockService = $this->createMock(EClaimsServiceHIE::class);
        $mockService->method('getDoctorPAN')
            ->willReturn($this->successfulResult());

        App::bind(EclaimsServiceInterface::class, function() use ($mockService) {
            return $mockService;
        });

        $person = $this->providePerson();
        $response = $this->executeQuery($this->query(), [
            'tin' => $this->faker->creditCardNumber,
            'lastName' => $person->last_name,
            'firstName' => $person->first_name,
            'middleName' => $person->middle_name,
            'suffix' => $person->suffix,
            'birthDate' => (string) Carbon::parse($person->birth_date),
        ]);

        $result = $response->json();
        $this->assertEquals($this->successfulResult(), array_get($result, 'data.getDoctorPAN'));
    }

    /**
     * @test
     */
    public function shouldHandleEmptyServiceCallResult()
    {
        $mockService = $this->createMock(EClaimsServiceHIE::class);
        $mockService->method('getDoctorPAN')
            ->willReturn($this->emptyResult());

        App::bind(EclaimsServiceInterface::class, function() use ($mockService) {
            return $mockService;
        });

        $person = $this->providePerson();
        $response = $this->executeQuery($this->query(), [
            'tin' => $this->faker->creditCardNumber,
            'lastName' => $person->last_name,
            'firstName' => $person->first_name,
            'middleName' => $person->middle_name,
            'suffix' => $person->suffix,
            'birthDate' => (string) Carbon::parse($person->birth_date),
        ]);

        $result = $response->json();
        $this->assertContains('No matching record found', array_get($result, 'errors.0.message'));
    }

    /**
     * @test
     */
    public function shouldHandleErrorInServiceCallResult()
    {
        $mockService = $this->createMock(EClaimsServiceHIE::class);
        $mockService->method('getDoctorPAN')
            ->willReturn($this->errorResult());

        App::bind(EclaimsServiceInterface::class, function() use ($mockService) {
            return $mockService;
        });

        $person = $this->providePerson();
        $response = $this->executeQuery($this->query(), [
            'tin' => $this->faker->creditCardNumber,
            'lastName' => $person->last_name,
            'firstName' => $person->first_name,
            'middleName' => $person->middle_name,
            'suffix' => $person->suffix,
            'birthDate' => (string) Carbon::parse($person->birth_date),
        ]);

        $result = $response->json();
        $this->assertContains('error message', array_get($result, 'errors.0.message'));
    }

    /**
     *
     */
    protected function query()
    {
        return <<<'GRAPHQL'
mutation GetDoctorPAN(
  $tin: String
  $lastName: String
  $firstName: String
  $middleName: String
  $suffix: String
  $birthDate: String
) {
  getDoctorPAN(
    tin: $tin
    lastName: $lastName
    firstName: $firstName
    middleName: $middleName
    suffix: $suffix
    birthDate: $birthDate
  )
}
GRAPHQL;
    }

    /**
     * @return string
     */
    private function successfulResult() : string
    {
        return '0000-0000000-0';
    }

    /**
     * @return string
     */
    private function emptyResult() : string
    {
        return '';
    }

    /**
     * @return string
     */
    private function errorResult() : string
    {
        return 'ERROR: <error message>';
    }

}
