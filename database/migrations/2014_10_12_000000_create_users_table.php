<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @author 
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('login')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('job',['VISITOR', 'EMPLOYEE', 'ADMINISTRATOR', 'SUPERADMIN']);;
            $table->string('tel')->nullable();
            $table->string('language')->nullable();
            $table->enum('gender',['F', 'M']);
            $table->unsignedBigInteger('service_id')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->string('signature')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('service_id')->references('id')->on('services')
                ->onUpdate('cascade')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
