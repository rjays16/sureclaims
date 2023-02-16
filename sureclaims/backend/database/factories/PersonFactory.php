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

$factory->define(\App\Model\Entities\Person::class, function (Faker $faker) {
    $sex = rand(0, 100) > 50 ? 'M' : 'F';
    return [
        'last_name' => $faker->lastName,
        'first_name' => $sex === 'M' ? $faker->firstNameMale : $faker->firstNameFemale,
        'middle_name' => $faker->lastName,
        'birth_date' => $faker->dateTimeThisCentury->format('Y-m-d'),
        'suffix' => '',
        'sex' => $sex,
        'email_address' => $faker->email,
        'mailing_address' => $faker->address,
        'zip_code' => rand(1000, 9999),
        'land_line_no' => $faker->phoneNumber,
        'mobile_no' => $faker->phoneNumber,
    ];
});
