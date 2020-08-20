<?php

use App\Models\Folder\FileType;
use Illuminate\Database\Seeder;

class FileTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
       /* factory(FileType::class, 10)->make()->each(function ($file_type) use ($faker) {
            $folder_types=App\Models\Folder\FolderType::all();
            $file_type->folder_type_id = $faker->randomElement($folder_types)->id;
            $file_type->save();
        });*/

    }
}