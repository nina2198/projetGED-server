<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Folder\FileType;
use Faker\Generator as Faker;

$factory->define(FileType::class, function (Faker $faker) {
    return [
        'name' => $faker->text(10),
        'description' => $faker->sentence(),
        'max_size' => $faker->numberBetween(500000, 9004000)
    ]; 
});
