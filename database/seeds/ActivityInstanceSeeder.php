<?php

use Illuminate\Database\Seeder;
use App\Models\Activity\ActivityInstance;

class ActivityInstanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    /*public function run(\Faker\Generator $faker)
    {
        factory(ActivityInstance::class, 30)->make()->each(function ($activity_instance) use ($faker) {
            $activities = App\Models\Activity\Activity::all();
            $folders = App\Models\Folder\Folder::all();
            $services = App\Models\Service\Service::all();
            $users = App\Models\Person\User::all();

            $activity_instance->activity_id = $faker->randomElement($activities)->id;
            $activity_instance->folder_id = $faker->randomElement($folders)->id;
            $activity_instance->service_id = $faker->randomElement($services)->id;
            $activity_instance->user_id = $faker->randomElement($users)->id;
            $activity_instance->save();
        });
    }*/
}
