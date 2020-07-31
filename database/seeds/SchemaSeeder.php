<?php

use Illuminate\Database\Seeder;
use App\Models\Schema\Schema;

class SchemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Models\Schema\Schema', 5)->create();
    }
}
