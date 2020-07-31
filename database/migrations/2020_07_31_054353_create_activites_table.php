<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activites', function (Blueprint $table) {
            $table->unsignedBigInteger('idActivite');
            $table->unsignedBigInteger('idService');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('idService')->references('idService')->on('services');
            $table->primary(['idActivite']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activites');
    }
}
