<?php

use App\Models\Folder\FolderType;
use Illuminate\Database\Seeder;

class FolderTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
        /*factory(FolderType::class, 10)->make()->each(function ($folder_type) use ($faker) {
            $schemas = App\Models\Schema\Schema::all();
            $folder_type->schema_id = $faker->randomElement($schemas)->id;
            $folder_type->save();
        });*/
    }
}
