<?php

use Illuminate\Database\Seeder;
use App\Models\Activity\Activites;

class ActivitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
        factory('App\Models\Activity\Activites', 20)->create();
    }
}
