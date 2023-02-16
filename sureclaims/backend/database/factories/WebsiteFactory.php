<?php

use Faker\Generator as Faker;
use Hyn\Tenancy\Models\Website;

$factory->define(Website::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
    ];
});
