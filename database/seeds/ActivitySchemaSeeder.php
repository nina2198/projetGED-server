<?php

use Illuminate\Database\Seeder;
use App\Models\Activity\ActivitySchema;

class ActivitySchemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    /*public function run(\Faker\Generator $faker)
    {
        factory(ActivitySchema::class, 20)->make()->each(function ($activity_schema) use ($faker) {
            $activities = App\Models\Activity\Activity::all();
            $schemas = App\Models\Schema\Schema::all();

            $activity_schema->activity_id = $faker->randomElement($activities)->id;
            $activity_schema->schema_id = $faker->randomElement($schemas)->id;
            $activity_schema->save();
        });
    }*/
}
