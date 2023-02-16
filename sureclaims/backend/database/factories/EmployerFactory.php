<?php

use Faker\Generator as Faker;

$factory->define( \App\Model\Entities\Employer::class, function ( Faker $faker ) {
    return [
        'pen' => substr( $faker->creditCardNumber, 1, 12 ),
        'name' => $faker->company,
        'address' => $faker->address,
    ];
} );
