<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Activites;
use Faker\Generator as Faker;

$factory->define(Activites::class, function (Faker $faker) {
    return [
        'idService' => $faker->number,
        'description' => $faker->unique()->text(30),
    ];
});
