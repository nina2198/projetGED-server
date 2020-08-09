<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\Activity\Activity;

$factory->define(Activity::class, function (Faker $faker) {
    return [
        'service_id' => $faker->numberBetween(1, 20),
        'description' => $faker->sentence(),
    ];

});
