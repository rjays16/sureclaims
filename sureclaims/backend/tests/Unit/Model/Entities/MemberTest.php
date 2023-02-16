<?php

/**
 * MemberTest.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Unit\Model\Entities;

use App\Model\Entities\Member;
use App\Model\Entities\Person;
use Tests\Concerns\InteractsWithTenancy;
use Tests\Concerns\ProvidesModels;
use Tests\Concerns\RefreshesDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class MemberTest
 * @package Tests\Unit\Model\Entities
 */
class MemberTest extends TestCase
{
    use InteractsWithTenancy,
        ProvidesModels,
        RefreshesDatabase,
        WithFaker;

    /**
     *
     */
    public function setUp() : void
    {
        parent::setUp();
        $this->setUpTenancy();
    }

    /**
     * @test
     */
    public function shouldCopyPrincipalMemberData()
    {
        $principalPerson = $this->providePerson();
        $principalPerson->save();
        $principalMember = $this->provideMember();
        $principalMember->relation = Member::RELATION_TYPE_MEMBER;  // Member
        $principalMember->membership_type = 'NO';
        $principalMember->person()->associate($principalPerson)->save();

        // Sanity check: member data should have been associated with principal person
        $this->assertEquals($principalMember->person_id, $principalPerson->id);

        // Sanity check: also check if this person's principal member is himself
        $this->assertEquals($principalMember->principal_id, $principalPerson->id);

        // We now create a new dependent
        $dependent = $this->providePerson();
        $dependent->save();
        $dependentMemberData = $this->provideMember();
        $dependentMemberData->relation = 'C'; // Dependent is child of principal
        $dependentMemberData->membership_type = 'S'; // Implicitly set to a value different from principal's

        // Preserve values to array for testing later!
        $originalData = $this->members()->present($dependentMemberData);

        // Creatte associations
        $dependentMemberData->person()->associate($dependent)
            ->principal()->associate($principalPerson)->save();

        $this->persons()->parsePresenterIncludes(['member']);

        // Sanity check: dependent is a different person from principal
        $this->assertNotEquals($dependent->id, $principalPerson->id);

        $dependentData = $this->persons()->find($dependent->id);
        $principalData = $this->persons()->find($principalPerson->id);

        // Sanity check: dependent is a child
        $this->assertEquals(array_get($dependentData, 'member.relation'), 'C');

        // Check that dependent has the same member data as principal
        $this->assertEquals(
            array_only(
                array_get($dependentData, 'member'), ['pen', 'employerName', 'membershipType']
            ),
            array_only(
                array_get($principalData, 'member'), ['pen', 'employerName', 'membershipType']
            )
        );

        // Check that the saved member data is now different from the generated member data
        $this->assertNotEquals(
            array_only(
                array_get($dependentData, 'member'), ['pin', 'pen', 'employerName', 'membershipType']
            ),
            array_only(
                $originalData, ['pin', 'pen', 'employerName', 'membershipType']
            )
        );
    }

    /**
     * @test
     */
    public function shouldUpdateAllDependentsMemberData()
    {
        $principal = $this->providePerson();
        $principal->save();
        $principalMember = $this->provideMember();
        $principalMember->relation = Member::RELATION_TYPE_MEMBER;
        $principalMember->membership_type = 'NO';
        $principalMember->person()->associate($principal)->save();

        $that = $this;
        $dependents = $this->providePersons(3)->map(function(Person $dependent) use ($that, $principal) {
            $dependent->save();
            $this->provideMember()->person()->associate($dependent)->save();

            $data = $this->persons()->find($dependent->id);

            // Sanity check: all dependents' member data have been saved
            // and all associations have been successfully created
            $this->assertNotEmpty($data);
            $this->assertNotEmpty($dependent->member);

            // Associate to principal member
            $dependent->member->relation = 'C'; // Child
            $dependent->member->membership_type = 'S';
            $dependent->member->principal()->associate($principal)->save();

            $this->assertEquals($dependent->member->membership_type, $principal->member->membership_type);
            $this->assertEquals($dependent->member->membership_type, 'NO');

            return $dependent;
        });

        $this->assertSameSize([1, 2, 3], $dependents);

        /** @var Person $principal */
        $principal = $this->persons()->skipPresenter()->find($principal->id);
        $principal->member->membership_type = 'PG';
        $principal->member->save();

        $this->persons()->skipPresenter(false);
        foreach ($dependents as $dependent) {
            /** @var Person $dependent */
            $data = $this->persons()->find($dependent->id);
            $this->assertEquals(array_get($data, 'member.membershipType'), 'PG');
        }
    }
}
