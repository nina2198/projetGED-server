<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchemaActivitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schema_activites', function (Blueprint $table) {
            $table->unsignedBigInteger('idSchemaActivites');
            $table->unsignedBigInteger('idActivite');
            $table->unsignedBigInteger('ordrePlacement');
            $table->timestamps();
            
            $table->foreign('idSchemaActivites')->references('idSchema')->on('schemas');
            $table->foreign('idActivite')->references('idActivite')->on('activites');
            $table->primary(['idSchemaActivites', 'idActivite']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schema_activites');
    }
}
