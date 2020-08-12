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
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('folder_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['PENDING', 'ACCEPTED', 'FINISH', 'REJECTED'])->default('PENDING');
            // EN ATTENTE, TRAITEMENT, TERMINEE, REJETEE
            $table->timestamps(); 
            
            $table->foreign('activity_id')->references('id')->on('activities')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('folder_id')->references('id')->on('folders')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::dropIfExists('activity_instances');
    }
}
