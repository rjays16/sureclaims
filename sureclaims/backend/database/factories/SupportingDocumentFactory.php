<?php

use Faker\Generator as Faker;

$factory->define(\App\Model\Entities\SupportingDocument::class, function (Faker $faker) {
    return [
        'storage_uid' => $faker->uuid,
        'document_type' => strtoupper($faker->lexify('???')),
        'file_name' => implode('-', $faker->words(3)) . '.pdf',
        'file_size' => $faker->randomNumber(),
        'public_path' => $faker->url,
        'hash' => md5($faker->word),
        'encrypted_hash' => md5($faker->word),
    ];
});
