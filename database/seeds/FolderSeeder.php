<?php

use App\Models\Folder\Folder;
use Illuminate\Database\Seeder;

class FolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
      /*  factory(Folder::class, 10)->make()->each(function ($folder) use ($faker) {
            $users = App\Models\Person\User::all();
            $folder_types = App\Models\Folder\FolderType::all();

            $folder->user_id = $faker->randomElement($users)->id;
            $folder->folder_type_id = $faker->randomElement($folder_types)->id;
            $folder->save();
        });*/
    }
}
