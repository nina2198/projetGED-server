<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Activity\ActivitySchema;
use Faker\Generator as Faker;

$factory->define(ActivitySchema::class, function (Faker $faker) {
    return [
        'activity_order' => $faker->numberBetween(2, 8)
    ];
});
