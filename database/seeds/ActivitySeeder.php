<?php

use Illuminate\Database\Seeder;

class ActivitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
        factory('App\Models\Activity\Activity', 20)->create();
    }
}
