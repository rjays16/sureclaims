<?php

use Faker\Generator as Faker;

$sampleXml = @file_get_contents(base_path('tests/resources/xml/claim-from-phic.xml'));
$transformer = new \App\Model\Transformers\ClaimXmlTransformer();

$factory->define(
    \App\Model\Entities\Claim::class,
    function (Faker $faker) use ($sampleXml, $transformer) {
        return [
            //
            'claim_no' => $faker->randomNumber(5),
            'admission_date' => $faker->dateTimeThisMonth->format('Ymd'),
            'discharge_date' => $faker->dateTimeThisMonth->format('Ymd'),
            'data_json' => $transformer->jsonize($sampleXml),
        ];
    }
);
