<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use  App\Models\Service\Service;
use Faker\Generator as Faker;

$factory->define(Service::class, function (Faker $faker) {
    return [
        'name'=> $faker->unique()->company,
    ];
});
