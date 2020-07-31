<?php

use Illuminate\Database\Seeder;
use App\Model\Service\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Model\Service\Service', 15)->create();
    }
}
