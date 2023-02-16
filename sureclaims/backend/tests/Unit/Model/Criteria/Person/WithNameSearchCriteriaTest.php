<?php

namespace Tests\Unit\Model\Criteria\Person;

use App\Model\Criteria\Person\WithNameSearchCriteria;
use App\Model\Entities\Person;
use App\Model\Repositories\Contracts\PersonRepository;
use Tests\Concerns\RefreshesDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class WithNameSearchCriteriaTest extends TestCase
{
    use RefreshesDatabase, WithFaker;

    /** @var PersonRepository */
    private $persons;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->persons = \App::make(PersonRepository::class);
    }

    /**
     * @test
     */
    public function shouldFilterLastNameAndFirstName()
    {
        // Dummy data
        factory(Person::class, 10)->create();

        // Add another 3 persons that will be filtered
        $count = 3;
        $prefix = $this->faker->lexify('?????');
        $person = factory(Person::class, $count)->make()->each(function (Person $person) use ($prefix) {
            $person->last_name = $prefix . $person->last_name;
            $person->first_name = $prefix . $person->first_name;
            $person->save();
        });

        $this->persons->resetScope();
        $search = "{$prefix}, {$prefix}";
        $criteria = new WithNameSearchCriteria($search);
        $this->persons->pushCriteria($criteria);

        $result = $this->persons->all();
        $this->assertSameSize(array_get($result, 'persons'), range(1, $count));
    }
}
