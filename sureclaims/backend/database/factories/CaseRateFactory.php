<?php

use App\Model\Entities\CaseRate;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define( CaseRate::class, function ( Faker $faker ) {
    return [
        'code' => $faker->postcode,
        'description' => $faker->paragraph,
        'item_code' => $faker->postcode,
        'item_description' => $faker->paragraph,
        'hci_fee' => $faker->numberBetween( 1, 100 ),
        'prof_fee' => $faker->numberBetween( 1, 100 ),
        'allow_second' => $faker->boolean(),
        'secondary_hci_fee' => $faker->numberBetween( 1, 100 ),
        'secondary_prof_fee' => $faker->numberBetween( 1, 100 ),
        'effectivity_date' => $faker->date(),
        'case_type' => $faker->randomElement( [ 'icd', 'rvs' ] ),
    ];
} );
