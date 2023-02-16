<?php

use Faker\Generator as Faker;

$factory->define(\App\Model\Entities\Transmittal::class, function (Faker $faker) {
    return [
        'transmittal_no' => $faker->creditCardNumber,
        'transmit_date' => $faker->dateTimeThisMonth(),
        'transmit_time' => $faker->dateTime,
        'transmittal_control_code' => $faker->creditCardNumber,
        'receipt_ticket_no' => $faker->creditCardNumber,
        'notes' => $faker->paragraph,
        'transmit_errors' => json_encode($faker->paragraphs(3)),
    ];
});
