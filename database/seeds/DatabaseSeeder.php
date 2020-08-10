<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        Schema::disableForeignKeyConstraints();

        // $this->call(CityAndCountrySeeder::class);
        
        // module person
        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            RoleUserSeeder::class,
            PermissionUserSeeder::class,
            PermissionRoleSeeder::class,
        ]);
        
        //module folder
        $this->call([
            FolderTypeSeeder::class,
            FolderSeeder::class,
            FileTypeSeeder::class,
            FileSeeder::class
        ]);
    
        //module Schema
        $this->call([
            SchemaSeeder::class
        ]);

        //module Serice
        $this->call([
            ServiceSeeder::class
        ]);
      
      //module Activites
      $this->call([
            ActivitesSeeder::class,
      ]);

        Schema::enableForeignKeyConstraints();
        Model::reguard();
    }
}
