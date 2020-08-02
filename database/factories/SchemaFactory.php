<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Schema\Schema;
use Faker\Generator as Faker;

$factory->define(Schema::class, function (Faker $faker) {
    return [
        'name'=> $faker->text(20),
        'description'=> $faker->sentence(),
        'nombre_service'=> $faker->numberBetween(2, 8),
    ];
});
