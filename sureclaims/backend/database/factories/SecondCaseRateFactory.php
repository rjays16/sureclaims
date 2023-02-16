<?php

use App\Model\Entities\SecondCaseRate;
use Faker\Generator as Faker;

$factory->define( SecondCaseRate::class, function ( Faker $faker ) {
    return [
        'code' => $faker->postcode,
        'description' => $faker->paragraph,
        'item_code' => $faker->postcode,
        'item_description' => $faker->paragraph,
        'hci_fee' => $faker->numberBetween( 1, 100 ),
        'prof_fee' => $faker->numberBetween( 1, 100 ),
        'effectivity_date' => $faker->date(),
        'case_type' => $faker->randomElement( [ 'icd', 'rvs' ] ),
    ];
} );
