<?php

use App\Models\Person\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
        /*factory(User::class, 30)->make()->each(function ($user) use ($faker) {
            $services = App\Models\Service\Service::all();
            $user->service_id = $faker->randomElement($services)->id;
            $user->save();
        });*/
        
    }
}
