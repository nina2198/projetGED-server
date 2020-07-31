<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Schema\Schema;
use Faker\Generator as Faker;

$factory->define(Schema::class, function (Faker $faker) {
    return [
        'nomSchema'=> $faker->unique()->text(20),
        'description'=> $faker->sentence(),
        'nbreService'=> $faker->numberBetween(2, 8),
    ];
});
