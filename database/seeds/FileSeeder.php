<?php

use App\Models\Folder\File;
use Illuminate\Database\Seeder;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
        factory(File::class, 10)->make()->each(function ($file) use ($faker) {
            $folders = App\Models\Folder\Folder::all();
            $file->folder_id = $faker->randomElement($folders)->id;
            $file->save();
        });
    }
}
