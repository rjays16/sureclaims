<?php

use Faker\Generator as Faker;

$factory->define(\App\Model\Entities\IcdCode::class, function (Faker $faker) {
    return [
        'code' => $faker->creditCardNumber,
        'description' =>  $faker->paragraph,
    ];
});
