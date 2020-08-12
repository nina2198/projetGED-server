<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Schema\Schema;
use Faker\Generator as Faker;

$factory->define(Schema::class, function (Faker $faker) {
    return [
        'nb_activities'=> $faker->numberBetween(2, 8),
        'name'=> $faker->text(20),
    ];
});
