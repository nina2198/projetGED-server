<?php

use Illuminate\Database\Seeder;
use App\Models\Service\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
        factory(Service::class, 20)->make()->each(function ($service) use ($faker) {
            $service->save();
        });
    }
}
