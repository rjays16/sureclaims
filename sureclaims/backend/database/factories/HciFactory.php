<?php

use Faker\Generator as Faker;

$factory->define(\App\Model\Entities\Hci::class, function (Faker $faker) {
    return [
        'accreditation_code' => $faker->creditCardNumber,
        'pmcc_no' => $faker->creditCardNumber,
        'hospital_name' => $faker->company,
    ];
});
