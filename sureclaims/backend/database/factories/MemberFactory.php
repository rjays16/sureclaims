<?php

use Faker\Generator as Faker;

$factory->define(\App\Model\Entities\Member::class, function (Faker $faker) {
    return [
        'pin' => $faker->creditCardNumber(),
        'membership_type' => $faker->randomElement([
            'S',
            'G',
            'I',
            'NS',
            'NO',
            'PS',
            'PG',
            'P'
        ]),
        'relation' => $faker->randomElement([
            'M', 'S', 'C', 'P',
        ]),
        'pen' => $faker->randomNumber(8),
        'employer_name' => $faker->company,
    ];
});
