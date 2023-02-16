<?php

/**
 * EligibilitiesQueryTest.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Feature\GraphQL\Queries;

use App\Model\Entities\Eligibility;
use Tests\Feature\GraphQL\GraphQLTestCase;

/**
 * Class EligibilitiesQueryTest
 *
 * @package Tests\Feature\GraphQL\Queries
 */
class EligibilitiesQueryTest extends GraphQLTestCase
{
    /**
     * @test
     */
    public function shouldReturnCorrectStructure()
    {
        $person = $this->providePerson();
        $person->save();
        $this->provideEligibilities(10)->each(function(Eligibility $eligibility) use ($person) {
            $eligibility->patient()->associate($person)->save();
        });
        $response = $this->executeQuery($this->query(), [
            'patient' => $person->id,
        ]);

        $result = $response->json();

        // No errors
        $this->assertEmpty(array_get($result, 'errors'));

        // We expect 5 items
        $data = array_get($result, 'data.eligibilities');
        $this->assertSameSize(range(1, 5), $data);
    }

    /**
     * @return string
     */
    private function query() : string
    {
        return <<<'GRAPHQL'
query Eligibilities($patient: ID!) {
  eligibilities(patient: $patient) {
    ...EligibilityData
  }
}

fragment EligibilityData on Eligibility {
  id
  isOk
  trackingNumber
  isNHTS
  with3Over6
  with9Over12
  remainingDays
  asOf
  patientIs
  patientLastName
  patientFirstName
  patientMiddleName
  patientSuffix
  patientBirthDate
  admitted
  discharged
  pin
  memberType
  memberLastName
  memberFirstName
  memberMiddleName
  memberSuffix
  memberBirthDate
  memberCategoryId
  memberCategoryDesc
  pen
  employerName
  requiredDocuments {
    code
    name
    reason
  }
  createdAt
  updatedAt
  __typename
}
GRAPHQL;

    }
}
