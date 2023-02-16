<?php

use Faker\Generator as Faker;

$factory->define(\App\Model\Entities\Eligibility::class, function (Faker $faker) {
    return [
        'checked_at' => $faker->dateTimeThisMonth,
        'is_ok' => $faker->boolean,
        'result_data' => null,
    ];
});
