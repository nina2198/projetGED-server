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
        /*factory(File::class, 10)->make()->each(function ($file) use ($faker) {
            $folders = App\Models\Folder\Folder::all();
            $file->folder_id = $faker->randomElement($folders)->id;
            $file_types = App\Models\Folder\FileType::all();
            $file->file_type_id = $faker->randomElement($file_types)->id;
            
            $file->save();
        });*/

        DB::table('files')->insert([
            'name' => 'Certificat de présence effectif',
            'description' => 'Document qui permet d\'établir la présence effectif d\'un enseignant a ses cours',
            'file_type' => 'PDF',
            'max_size' => 2097152,
            'folder_type_id' => 1,
        ]);
    }
}
