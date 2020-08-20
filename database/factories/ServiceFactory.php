<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use  App\Models\Service\Service;
use Faker\Generator as Faker;

$factory->define(Service::class, function (Faker $faker) {
    return [
        'admin_id' => factory(App\Models\Person\User::class),
        'name'=> $faker->unique()->company,
        'description'=> $faker->paragraph,
        'building'=> $faker->address,
    ];
});
