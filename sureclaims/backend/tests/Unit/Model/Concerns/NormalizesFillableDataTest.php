<?php

/**
 * NormalizesFillableDataTest.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Unit\Model\Concerns;

use App\GraphQL\Concerns\NormalizesFillableData;
use App\Model\Entities\Person;
use Tests\Concerns\InteractsWithTenancy;
use Tests\Concerns\ProvidesModels;
use Tests\Concerns\RefreshesDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class NormalizesFillableDataTest
 *
 * @package Tests\Unit\Model\Concerns
 */
class NormalizesFillableDataTest extends TestCase
{
    use ProvidesModels,
        NormalizesFillableData;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function shouldNormalizeDateFields()
    {
        $person = $this->providePerson();
        $data = $this->persons()->present($person);
        $normalized = $this->normalizeFillableData($person, $data);

        $this->assertInstanceOf(\DateTime::class, array_get($normalized, 'birth_date'));
    }
}
