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

        //module Serice
        /*$this->call([
            ServiceSeeder::class
        ]);
        
        // module person
        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            RoleUserSeeder::class,
            PermissionUserSeeder::class,
            PermissionRoleSeeder::class,
        ]);

        //module Schema
        $this->call([
            SchemaSeeder::class
        ]);
        
        //module folder
        $this->call([
            FolderTypeSeeder::class,
            FolderSeeder::class,
            FileTypeSeeder::class,
            FileSeeder::class
        ]);

        //module Services
        $this->call([
            ServiceSeeder::class
        ]);
      
        //module Activites
        $this->call([
            ActivitySeeder::class,
            ActivityInstanceSeeder::class,
        ]);

        $this->call([
            ActivitySchemaSeeder::class,
        ]);*/
        DB::table('users')->delete();
        DB::table('users')->insert([
            'id' => 1,
            'login' => 'admin',
            'job' => 'SUPERADMIN',
            'first_name' => Str::random(10),
            'last_name' => Str::random(10),
            'gender' => 'M',
            'language' => 'fr',
            'avatar' =>url('/uploads/users-avatar/use.superadmin.jpg'),
            'email' => Str::random(10).'@gmail.com',
            'password' => bcrypt('password'),
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'login' => 'admin_MI',
            'job' => 'ADMINISTRATOR',
            'first_name' => 'Kenfack',
            'last_name' => 'Marcelin',
            'gender' => 'M',
            'language' => 'fr',
            'avatar' =>url('/uploads/users-avatar/use.superadmin.jpg'),
            'email' => Str::random(10).'@gmail.com',
            'password' => bcrypt('password'),
        ]);

        DB::table('users')->insert([
            'id' => 3,
            'login' => 'admin_fs', //le doyen de la faculte des sciences
            'job' => 'ADMINISTRATOR',
            'first_name' => 'Pr Kameni',
            'last_name' => 'Emmanuel',
            'gender' => 'M',
            'language' => 'fr',
            'avatar' =>url('/uploads/users-avatar/use.superadmin.jpg'),
            'email' => Str::random(10).'@gmail.com',
            'password' => bcrypt('password'),
        ]);

        DB::table('users')->insert([
            'id' => 4,
            'login' => 'admin_DAAC',
            'job' => 'ADMINISTRATOR',
            'first_name' => 'Ngomseu',
            'last_name' => 'Romaric',
            'gender' => 'M',
            'language' => 'en',
            'avatar' =>url('/uploads/users-avatar/use.superadmin.jpg'),
            'email' => 'ngomseuromaric@gmail.com',
            'password' => bcrypt('password'),
        ]);

        DB::table('users')->insert([
            'id' => 5,
            'login' => 'recteur_uds', //le recteur de l'universite
            'job' => 'ADMINISTRATOR',
            'first_name' => Str::random(10),
            'last_name' => Str::random(10),
            'gender' => 'M',
            'language' => 'fr',
            'avatar' =>url('/uploads/users-avatar/use.superadmin.jpg'),
            'email' => Str::random(10).'@gmail.com',
            'password' => bcrypt('password'),
        ]);

        DB::table('users')->insert([
            'id' => 6,
            'login' => 'secretaire', //le recteur de l'universite
            'job' => 'EMPLOYEE',
            'first_name' => 'Tsafack Nafosso',
            'last_name' => 'Roger Antoine Pépin',
            'gender' => 'M',
            'avatar' =>url('/uploads/users-avatar/use.superadmin.jpg'),
            'email' => 'uds_rectorat@gmail.com',
            'password' => bcrypt('password'),
        ]);

        /**les seeds de la table des services */
        DB::table('services')->delete();
    /*  DB::table('services')->insert([
            'id' => 1,
            'name' => 'Département de Biologie Animale ',
            'description' => 'Le département de Biologie Animale regroupe un ensemble d\'enseignants et du personnel
            avec deux options en Licence : Zoologie et Physiologie Animales, et trois options en Master et Doctorat.',
        ]);

        DB::table('services')->insert([
            'id' => 2,
            'name' => 'Département de Biochimie ',
            'description' => 'Le département de Biochimie regroupe un ensemble d\'enseignants et du personnel 
            avec 3 options en Licence,  Master et Doctorat: Biochimie clinique, Nutrition et sécurité alimentaires 
            et pharmacologie. Master professionnelle avec 2 options :  nutrition clinique et diététique et nutrition 
            communautaire.',
        ]);

        DB::table('services')->insert([
            'id' => 3,
            'name' => 'Département de Biologie Vegetale ',
            'description' => 'Le département de Biologie Vegetale regroupe un ensemble d\'enseignants et du personnel, 
            avec deux options en Licence et Master, biologie et physiologie végétales. Master professionnelle en assainissement urbain.',
        ]);

        DB::table('services')->insert([
            'id' => 4,
            'name' => 'Département de Chimie ',
            'description' => 'Le département de Chimie regroupe un ensemble d\'enseignants et du personnel, 
            avec 2 options : chimie organique, chimie inorganique.',
        ]);
    */
        DB::table('services')->insert([
            'id' => 5,
            'admin_id' => 2,
            'name' => 'Département de Mathematiques-Informatique ',
            'description' => 'Le département de Mathematiques-Informatique regroupe un ensemble d\'enseignants et du personnel des 
            filières Mathematiques et Informatique, avec deux options en Licence, Master et Doctorat.',
        ]);
    /*
        DB::table('services')->insert([
            'id' => 6,
            'name' => 'Département de Physique ',
            'description' => 'Le département de Physique regroupe un ensemble d\'enseignants et du personnel 
            avec 3 options  en Licence, Master et Doctorat :Mécanique-Energétique, Electronique-Electrotechnique-Automatique,  
            et   Matière  Condensée.',
        ]);

        DB::table('services')->insert([
            'id' => 7,
            'name' => 'Département de Science de la terre ',
            'description' => 'avec 2 options en Licence, Master et Doctorat : géotechnique et valorisation des matériaux d’une part, 
            et hydrologie et aménagement des ressources naturelles d’autre part.  Master professionnelle avec 2 options : géologie appliquée et mines et pétrole.',
        ]);

        DB::table('services')->insert([
            'id' => 8,
            'name' => 'Département de Sciences de l’Ingénieur',
            'description' => ' Le département de Science de l\'ingenieur regroupe un ensemble d\'enseignants et du personnel avec les options en Master : Géologue appliquée ,  Mines et Pétrole,nutrition clinique et diététique,assainissement urbain et nutrition communautaire.',
        ]);
    */
        DB::table('services')->insert([
            'id' => 9,
            'admin_id' => 3,
            'name' => 'Bureau du Doyen',
            'description' => 'Le service du doyen de la faculte des sciences',
        ]);

        DB::table('services')->insert([
            'id' => 10,
            'admin_id' => 4,
            'name' => 'DAAC',
            'description' => 'Direction des affaires academique et de la cooperation',
        ]);

        DB::table('services')->insert([
            'id' => 11,
            'admin_id' => 5,
            'name' => 'Rectorat',
            'description' => 'La direction de l\'universite de Dschang',
        ]);

/********************************************************************* */
        DB::table('users')->where('id', '=', 2)
        ->update([
            'service_id'=> 5,
        ]);

        DB::table('users')->where('id', '=', 3)
        ->update([
            'service_id'=> 9,
        ]);

        DB::table('users')->where('id', '=', 4)
        ->update([
            'service_id'=> 10,
        ]);

        DB::table('users')->where('id', '=', 5)
        ->update([
            'service_id'=> 11,
        ]);

        DB::table('users')->where('id', '=', 6)
        ->update([
            'service_id'=> 5,
        ]);


        DB::table('services')->where('id', '=', 5)
                ->update([ 'admin_id'=> 2,]);

        DB::table('services')->where('id', '=', 9)
        ->update([
            'admin_id'=> 3,
        ]);

        DB::table('services')->where('id', '=', 10)
        ->update([
            'admin_id'=> 4,
        ]);

        DB::table('services')->where('id', '=', 11)
        ->update([
            'admin_id'=> 5,
        ]);


/********************************************************************* */

        /**les seeds du schema */
        DB::table('schemas')->delete();
        DB::table('schemas')->insert([
            'id' => 1,
            'name' => 'Demande d\'abscence de 3 jours',
            'nb_activities' => 2,
        ]);

        DB::table('schemas')->insert([
            'id' => 2,
            'name' => 'Dépot de dossier pour soutenance',
            'nb_activities' => 4,
        ]);

        /**les seeds des folders types */
        DB::table('folder_types')->delete();
        DB::table('folder_types')->insert([
            'name' => 'Demande d\'absence de 3 jours',
            'slug' => 'demande_dabsence_3_jours',
            'description' => 'Demande d\'absence par un enseignant en cas de maladie',
            'max_file_size' => 2097152*4,
            'file_number' => 4,
            'schema_id' => 1,
        ]);

        DB::table('folder_types')->insert([
            'name' => 'Dépot de dossier pour soutenance',
            'slug' => 'Dépot_de_dossier_pour_soutenance',
            'description' => 'Dépot de dossier pour soutenance par un etudiant de l\'universite de Dschang',
            'max_file_size' => 2097152*4,
            'file_number' => 7,
            'schema_id' => 2,
        ]);



        /**les seeds des files types */
        
        DB::table('file_types')->delete();
        DB::table('file_types')->insertGetId([
            'id' => 1,
            'name' => 'Certificat de présence effectif',
            'description' => 'Document qui permet d\'établir la présence effectif d\'un enseignant a ses cours',
            'file_type' => 'PDF',
            'max_size' => 2097152,
            'folder_type_id' => 1,
        ]);

        DB::table('file_types')->insert([
            'id' => 2,
            'name' => 'Procuration co-signé par l\'enseignant remplacant',
            'description' => 'Document signé par l\'ensegnant qui va remplacer celui qui demande l\'absence',
            'file_type' => 'PDF',
            'max_size' => 2097152,
            'folder_type_id' => 1,
        ]);

        DB::table('file_types')->insert([
            'name' => 'Ordre de Mission',
            'description' => 'Document contenant l\'ordre de mission ',
            'file_type' => 'PDF',
            'max_size' => 2097152,
            'folder_type_id' => 1,
        ]);
    
        DB::table('file_types')->insert([
            'name' => 'Invitation',
            'description' => 'Document précisant l\'institution ou la structure qui invite l\'enseignant qui fait la demande ',
            'file_type' => 'PDF',
            'max_size' => 2097152,
            'folder_type_id' => 1,
        ]);

        DB::table('file_types')->insert([
            'name' => 'Exemplaire de memoire signe par l\'encadreur',
            'description' => 'Le memoire précisant l\'institution ou la structure que l\'etudiant fait partit',
            'file_type' => 'PDF',
            'max_size' => 2097152,
            'folder_type_id' => 2,
        ]);

        DB::table('file_types')->insert([
            'name' => 'Lettre signée par l\'encadreur',
            'description' => 'Le lettre précisant le mot de l\'encadreur et sa signature',
            'file_type' => 'PDF',
            'max_size' => 2097152,
            'folder_type_id' => 2,
        ]);

        DB::table('file_types')->insert([
            'name' => 'Proposition du Jury',
            'description' => 'Contient une liste d\'enseignants que l\'etudiant souhaite avoir comme encadreur',
            'file_type' => 'PDF',
            'max_size' => 2097152,
            'folder_type_id' => 2,
        ]);

        DB::table('file_types')->insert([
            'name' => 'Recus de paiement',
            'description' => 'Recus de paiement des droits universitaires de l\'année encours',
            'file_type' => 'PDF',
            'max_size' => 2097152,
            'folder_type_id' => 2,
        ]);

        /**les seeds des activites */
        DB::table('activities')->delete();
        DB::table('activities')->insert([
            'id' => 1,
            'service_id' => 5,
            'name' => 'consultez et donnez votre approbation sur la demande d\'absence',
            'description' => 'cosultation par le chef de departement, verifiez l\'ensemble des documents',
        ]);

        DB::table('activities')->insert([
            'id' => 2,
            'service_id' => 9,
            'name' => 'consultez et approuvez ou rejettez la demande',
            'description' => 'cosultation par le chef de doyen, verifiez l\'ensemble des documents',
        ]);

        DB::table('activities')->insert([//departement
            'id' => 3,
            'service_id' => 5,
            'name' => 'consultez et donnez votre approbation et ajouter l\'etudiant sur la liste',
            'description' => 'cosultation par le chef de departement et fournit une liste en fin de traitement de ce dossier',
        ]);

        DB::table('activities')->insert([ //decanat
            'id' => 4,
            'service_id' => 9,
            'name' => 'verifiez et donnez votre approbation sur la liste et la transmettre',
            'description' => 'cosultation par le doyen et fournit une liste et la transmet',
        ]);

        DB::table('activities')->insert([
            'id' => 5,
            'service_id' => 10,
            'name' => 'verifiez conformité de la liste et soumettre au daac',
            'description' => 'cosultation par le chef service du daac et fournit une liste en fin de traitement',
        ]);

        DB::table('activities')->insert([
            'id' => 6,
            'service_id' => 11,
            'name' => 'consultez et signez la liste si tout est conforme',
            'description' => 'cosultation par le recteur de l\'universite de dschang et fournit la liste finale des demandes retenues',
        ]);

        /**les seeds de activity_schema */
        DB::table('activity_schema')->delete();
        DB::table('activity_schema')->insert([
            'schema_id' => 1,
            'activity_id' => 1,
            'activity_order' => 1,
        ]);

        DB::table('activity_schema')->insert([
            'schema_id' => 1,
            'activity_id' => 2,
            'activity_order' => 2,
        ]);

        DB::table('activity_schema')->insert([
            'schema_id' => 2,
            'activity_id' => 3,
            'activity_order' => 1,
        ]);

        DB::table('activity_schema')->insert([
            'schema_id' => 2,
            'activity_id' => 4,
            'activity_order' => 2,
        ]);
        DB::table('activity_schema')->insert([
            'schema_id' => 2,
            'activity_id' => 5,
            'activity_order' => 3,
        ]);

        DB::table('activity_schema')->insert([
            'schema_id' => 2,
            'activity_id' => 6,
            'activity_order' => 4,
        ]);



        Schema::enableForeignKeyConstraints();
        Model::reguard();
    }
}
