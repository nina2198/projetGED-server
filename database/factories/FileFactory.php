<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Folder\File;
use Faker\Generator as Faker;

$factory->define(File::class, function (Faker $faker) {
    return [
        'name' => $faker->text(10),
        'description' => $faker->sentence(),
        'file_size' => $faker->numberBetween(5000, 75000),
        'path' => $faker->randomElement(['C:\Users\bnina\Documents\projects\GED PROJECT\ged-server\public\new', 'C:\Users\bnina\Documents\projects\GED PROJECT\ged-server\public\old', 'C:\Users\bnina\Documents\projects\GED PROJECT\ged-server\public\recent']),
        'file_type' => $faker->randomElement(['PDF', 'PHOTO'])
    ];
});
