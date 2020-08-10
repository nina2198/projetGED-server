<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Folder\Folder;
use Faker\Generator as Faker;

$factory->define(Folder::class, function (Faker $faker) {
    return [
        'name' => $faker->text(10),
        'description' => $faker->sentence(),
        'status' => $faker->randomElement(['ACCEPTED', 'PENDING', 'REJECTED', 'ARCHIVED']),
        'track_id' => $faker->numberBetween(1000, 9999)
    ];
});
