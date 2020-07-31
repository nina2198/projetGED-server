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
            $table->unsignedBigInteger('idSchema');
            $table->unsignedBigInteger('idActivite');
            $table->unsignedBigInteger('ordrePlacement');
            $table->timestamps();
            
            $table->foreign('idSchema')->reference('idSchema')->on('schema');
            $table->foreign('idActivite')->reference('idActivite')->on('activites');
            $table->primary(['idSchema', 'idActivite']);
            $table->softDeletes();
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
