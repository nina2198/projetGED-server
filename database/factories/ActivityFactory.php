<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Activity\Activity;
use Faker\Generator as Faker;

$factory->define(Activity::class, function (Faker $faker) {
    return [
        'service_id'=> $faker->numberBetween(1, 20), 
        'description' => $faker->unique()->text(30),
    ];
});
