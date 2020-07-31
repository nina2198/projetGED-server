<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schemas', function (Blueprint $table) {
            $table->unsignedBigInteger('idSchema');
            $table->string('nomSchema');
            $table->text('description');
            $table->unsignedBigInteger('nbreService');
            $table->timestamps();

            $table->primary(['idSchema']) ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schemas');
    }
}
