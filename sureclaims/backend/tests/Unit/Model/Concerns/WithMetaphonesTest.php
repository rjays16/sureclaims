<?php

/**
 * WithMetaphonesTestt.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Unit\Lib;

use App\Model\Entities\Person;
use Tests\Concerns\RefreshesDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class WithMetaphonesTest extends TestCase
{
    use RefreshesDatabase;

    /**
     *
     * @test
     */
    public function shouldPopulateMetaphoneFields() : void
    {
        $person = factory(Person::class)->create();

        $this->assertEquals(
            metaphone($person->last_name),
            $person->last_name_metaphone
        );
    }

}
