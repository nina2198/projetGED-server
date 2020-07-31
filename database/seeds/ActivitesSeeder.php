<?php

use Illuminate\Database\Seeder;
use App\Activites;

class ActivitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
        factory('App\Activite', 5)->create();
    }
}
