<?php

use Faker\Generator as Faker;
use Hyn\Tenancy\Models\Hostname;

$factory->define(Hostname::class, function (Faker $faker) {
    return [
        //
        'fqdn' => $faker->domainName,
    ];
});
