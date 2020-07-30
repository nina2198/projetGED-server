<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Folder\FolderType;
use Faker\Generator as Faker;

$factory->define(FolderType::class, function (Faker $faker) {
    return [
        'name' => $faker->text(10),
        'description' => $faker->sentence(),
        'max_file_size' => $faker->numberBetween(500000, 9004000),
        'file_number' => $faker->numberBetween(1, 7)
    ]; 
});
