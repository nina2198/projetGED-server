<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_instances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('activity_id');
            $table->unsignedBigInteger('folder_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('user_id');

            $table->enum('status', ['WAITING', 'HANGING', 'ENDING', 'EXECUTION'])->default('WAITING');
            // EN ATTENTE, SUSPENDUE, TERMINAISON, EN EXECUTION
            $table->timestamps(); 
            $table->foreign('activity_id')->references('id')->on('activities');
            $table->foreign('folder_id')->references('id')->on('folders');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_instances');
    }
}
