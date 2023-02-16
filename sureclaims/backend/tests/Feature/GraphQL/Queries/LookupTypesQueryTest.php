<?php

/**
 * LookupTypesQueryTest.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Feature\GraphQL\Queries;

use App;
use App\Model\Repositories\Contracts\GlobalLookupRepository;
use Tests\Feature\GraphQL\GraphQLTestCase;

/**
 * Class LookupTypesQueryTest
 *
 * @package Tests\Feature\GraphQL
 */
class LookupTypesQueryTest extends GraphQLTestCase
{
    /** @var GlobalLookupRepository  */
    private $globalLookups;

    /**
     *
     */
    public function setUp() : void
    {
        parent::setUp();
        $this->globalLookups = App::make(GlobalLookupRepository::class);
        $this->globalLookups->createMany($this->globalLookupsData());
    }

    /**
     * @param string $domain
     *
     * @test
     * @dataProvider provideDomains
     */
    public function shouldReturnValidStructure($domain)
    {
        $response = $this->executeQuery($this->typesQuery($domain));
        $response->assertJsonStructure([
            'data' => [
                'lookupTypes' => [
                    [
                        'id',
                        'domain',
                        'type',
                        'value'
                    ]
                ]
            ]
        ]);
    }

    /**
     * @test
     * @dataProvider provideDomains
     */
    public function shouldReturnEmptyStructureIfNonExistent()
    {
        $response = $this->post('/graphql/test', [
            'query' => $this->typesQuery('NON EXISTENT DOMAIN'),
        ], [
            'Accepts' => 'application/json',
        ]);

        $response->assertJsonStructure([
            'data' => [
                'lookupTypes'
            ]
        ]);

        $json = $response->json();
        $this->assertEmpty($json['data']['lookupTypes']);
    }

    /**
     * @return array
     */
    public function provideDomains()
    {
        return [
            [ null ],
            ['global.subDomain']
        ];
    }

    /**
     * @param string $domain
     *
     * @return string
     */
    private function typesQuery($domain)
    {
        return <<<GRAPHQL
query LookupTypes {
  lookupTypes (
    domain: "{$domain}"
  ) {
    id
    domain
    type
    value
  }
}
GRAPHQL;
    }

    /**
     * @return array
     */
    private function globalLookupsData() : array
    {
        return [
            [
                'domain_name' => 'global.subDomain',
                'lookup_type' => 'test1',
                'lookup_value' => 'Test 1',
            ],
            [
                'domain_name' => 'global.subDomain',
                'lookup_type' => 'test2',
                'lookup_value' => 'Test 2',
            ]
        ];
    }
}
