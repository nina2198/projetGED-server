<?php

use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
        factory(Activity::class, 30)->make()->each(function ($activity) use ($faker) {
            $services = App\Models\Service\Service::all();
            $activity->service_id = $faker->randomElement($services)->id;
            $activity->save();
        });
    }
}
