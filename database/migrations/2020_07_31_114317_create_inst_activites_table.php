<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstActivitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inst_activites', function (Blueprint $table) {
            $table->unsignedBigInteger('idSchema');
            $table->unsignedBigInteger('idActivite');
            $table->unsignedBigInteger('idUser');
            $table->enum('status', ['WAITING', 'HANGING', 'ENDING', 'EXECUTION'])->default('WAITING');
            // EN ATTENTE, SUSPENDUE, TERMINAISON, EN EXECUTION
            $table->timestamps();
            
            $table->foreign('idSchema')->references('idSchema')->on('schemas');
            $table->foreign('idActivite')->references('idActivite')->on('activites');
            $table->foreign('idUser')->references('id')->on('users');
            $table->primary(['idActivite', 'idSchema', 'idUser']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inst_activites');
    }
}
