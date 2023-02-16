<?php

use Faker\Generator as Faker;
use Hyn\Tenancy\Models\Customer;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        //
        'name' => $faker->company,
        'email' => $faker->companyEmail,
    ];
});
