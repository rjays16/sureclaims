<?php

/**
 * CheckEligbilityMutationTest.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Feature\GraphQL\Mutations;

use App\Lib\Soap\EClaimsServiceHIE;
use App\Lib\Soap\EclaimsServiceInterface;
use App\Model\Entities\Member;
use App\Model\Entities\Person;
use App\Model\Repositories\Contracts\MemberRepository;
use App\Model\Repositories\Contracts\PersonRepository;
use Tests\Feature\GraphQL\GraphQLTestCase;

/**
 * Class CheckEligbilityMutationTest
 * @package Tests\Feature\GraphQL\Mutations
 */
class CheckEligbilityMutationTest extends GraphQLTestCase
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
     * @todo Change this test case
     */
    public function shouldReturnCorrectStructure()
    {
        $person = new Person([
            'last_name' => 'QUIÑONES',
            'first_name' => 'ALVIN JED',
            'middle_name' => 'MONSANTO',
            'sex' => 'M',
            'birth_date' => '1983-07-18',
            'mailing_address' => '#24 Knight Alley St., Royal Valley Subd., Bangkal, Davao City',
            'zip_code' => '8000'
        ]);
        $person->save();
        $member = new Member([
            'pin' => '010501145756',
            'relation' => 'M',
            'membership_type' => 'S',
            'pen' => '019000029665',
            'employer_name' => 'SAMPLE HOSPITAL'
        ]);
        $member->person()->associate($person);
        $member->principal()->associate($person);
        $member->save();

        $mockService = $this->createMock(EClaimsServiceHIE::class);
        $mockService->method('isClaimEligible')
            ->willReturn($this->expectedServiceResult());

        \App::bind(EclaimsServiceInterface::class, function() use ($mockService) {
            return $mockService;
        });

        $response = $this->executeQuery($this->query(), [
            'patient' => $person->id,
            'confinementDates' => ['03-01-2018', '03-08-2018'],
            'actualAmount' => 10000,
            'claimAmount' => 9999.50,
            'isFinal' => false,
        ]);

        $data = array_get($response->json(), 'data.checkEligibility');
        $this->assertNotEmpty($data);

        // Check date format
        $this->assertStringMatchesFormat('%c%c-%c%c-%c%c%c%c', $data['asOf']);

        $dataFromDb = $this->eligibilities()->find($data['id']);
        $this->assertEquals($data, $dataFromDb);
    }

    /**
     *
     */
    private function expectedServiceResult() : string
    {
        return '{"iv":"CTbv+2Ytub5HvkeVasV8ug==","docMimeType":"text\/xml","doc":"CTA\/hnvbdFf2+H4rCKJeSB1Vt7ZuUEyBQalOILnO7EGd7wBFNN8SRkfNzv\/upbXtEgHTdGgx5Kw4\r\nfd3PNkIdGg76nunmQPQKIugiYVx9cQgAHFgItBdiwvfLKWx36qROw49n\/Uvy4ixWWLqB3TurNrAB\r\nRQbIj65iLDlL82X8dXmqZxFUoo2V9XJ2tu2uhj2gF+4VFqTTjw+SZRmIv5HePxTxRQQ19tL5saZE\r\nnfKebXfx9CS3j1myk86TpyohkQmaeQl7Tv7uxGm2E6PcpeqxLo7UViBQ0JGvxRSLpZCSp5SVnh0N\r\n4jeg06rC5HQxsmLEtDn\/CsE+pOGwXe9kdlDPejUbTMg+wECu+UhCPjenQCnAa8+4b6SPIkdsvf+m\r\nw+RLxCikwf8ZN41HUeZ3QDHMsEpYiHeBPf9+TUCpzWXJJ0E1RNffO474AHQUV635YRsad58Wej9W\r\n5\/0onWQaIbYQ\/NCIpLTILmMb+h2cV2Ks3MoTFk83KaWBOwaK+vXIebS7USfHq2s7Gs18I0eRgdmv\r\npvdrd2f1BJUyrnez6N72UfaPMqVcpnnKpQcuyAr6Z2AaAha5NPYpBsXELqZyonvfttLWJxqR7YrA\r\nNMVvfwa4rH7FJ852UsPimgG8azAQKv4yKCoppGn1jk7VX869Bol8KEmKIrQeCmCX0AscYG9dFMoz\r\nYFN7YX5A9MKV7uz4e9zAgirkQ9K0ABVAto4qD3t9Xj3Eg\/ALfFKbX6itnp4zybLq+W8u1wiLkIeR\r\n9cGGXbQnDJinMYzYcJikwrFIrPTBWD+o3db\/ZzUbWATTxakbG3L5942cshg4tOhUcSyth8H+I2l0\r\nGnZv56OId5M1YppeMxZm7DJDcFN5uU0Q6QKn1Ggy0lJiujP2TRT5hARJKWRK+ZxFtu29ZJVa6ShZ\r\nZilHjcRoq3GrhDmkntGoydgL35g="}';
    }

    /**
     *
     */
    protected function query()
    {
        return <<<'GRAPHQL'
mutation CheckEligibility(
  $patient: String!
  $confinementDates: [String!]
  $rvs: String
  $actualAmount: Float!
  $claimAmount: Float!
  $isFinal: Boolean!
) {
  checkEligibility(
    patient: $patient
    confinementDates: $confinementDates
    rvs: $rvs
    actualAmount: $actualAmount
    claimAmount: $claimAmount
    isFinal: $isFinal
  ) {
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
  }
}

GRAPHQL;
    }
}
