<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\Activity\Activity;

$factory->define(Activity::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->text(20),
        'description' => $faker->sentence()
    ];

});
