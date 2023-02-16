<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\App\Model\Entities\Doctor::class, function (Faker $faker) {
    return [
        'tin' => $faker->creditCardNumber,
        'pan' => $faker->creditCardNumber,
        'last_name' => $faker->lastName,
        'first_name' => $faker->firstName,
        'middle_name' => $faker->lastName,
        'birth_date' => $faker->dateTimeThisCentury->format('Y-m-d'),
        'suffix' => $faker->randomElement(array_merge(
            array_map(function() { return ''; }, range(1, 20)),
            [
                'JR',
                'SR',
                'III',
                'IV'
            ]
        )),
    ];
});
