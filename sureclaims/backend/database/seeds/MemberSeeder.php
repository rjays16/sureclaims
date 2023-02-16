<?php

use App\Model\Entities\Member;
use App\Model\Entities\Person;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Member::class, 100)->make()->each(function (Member $member) {
            $member->person()->associate(
                factory(Person::class)->create()
            )->save();
        });
    }
}
