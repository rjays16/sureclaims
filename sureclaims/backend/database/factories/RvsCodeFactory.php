<?php

use App\Model\Entities\RvsCode;
use Faker\Generator as Faker;

$factory->define(RvsCode::class, function (Faker $faker) {
    return [

        'code' => $faker->creditCardNumber,
        'description' => $faker->paragraph(),
        'rvu' => $faker->randomDigit
    ];
});
