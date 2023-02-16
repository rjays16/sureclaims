<?php

/**
 * LookupsTest.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Unit\Lib;

use App;
use App\Lib\Contracts\LookupsInterface;
use App\Model\Repositories\Contracts\GlobalLookupRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Concerns\RefreshesDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class LookupsTest extends TestCase
{
    use RefreshesDatabase;

    /** @var GlobalLookupRepository  */
    private $globalLookups;

    /** @var LookupsInterface */
    private $lookup;

    /**
     *
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->globalLookups = App::make(GlobalLookupRepository::class);
        $this->globalLookups->createMany($this->globalLookupsData());
        $this->lookup = App::make(LookupsInterface::class);
    }

    /**
     * Tests if Lookup::types returns type and value
     *
     * @param string $domain
     *
     * @test
     * @dataProvider typesDataProvider
     */
    public function typesMethodShouldReturnTypeAndValue($domain) : void
    {
        $result = $this->lookup->types($domain);

        $this->assertEquals(2, sizeof($result));
        $this->assertNotEmpty(@$result[0]);

        $this->assertArrayHasKey('type', $result[0]);
        $this->assertArrayHasKey('value', $result[0]);
    }

    /**
     * @param string $domain
     * @param string $type
     *
     * @test
     * @dataProvider valueDataProvider
     */
    public function valueMethodShouldReturnTypeAndValue($domain, $type) : void
    {
        $result = $this->lookup->value($domain, $type);
        $this->assertInternalType('string', $result);
    }

    /**
     * @return array
     */
    public function typesDataProvider() : array
    {
        return [
            [ 'global.test_column' ],
        ];
    }

    /**
     * @return array
     */
    public function valueDataProvider() : array
    {
        return array_map(function ($row) {
            return [
                @$row['domain_name'],
                @$row['lookup_type'],
            ];
        }, $this->globalLookupsData());
    }

    /**
     * @return array
     */
    private function globalLookupsData() : array
    {
        return [
            [
                'domain_name' => 'global.test_column',
                'lookup_type' => 'test1',
                'lookup_value' => 'Test 1',
            ],
            [
                'domain_name' => 'global.test_column',
                'lookup_type' => 'test2',
                'lookup_value' => 'Test 2',
            ]
        ];
    }

}
