<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitySchemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_schema', function (Blueprint $table) {
            $table->unsignedBigInteger('schema_id');
            $table->unsignedBigInteger('activity_id');
            $table->unsignedBigInteger('activity_order');
            $table->timestamps();
            
            $table->foreign('schema_id')->references('id')->on('schemas');
            $table->foreign('activity_id')->references('id')->on('activities');
            $table->primary(['schema_id', 'activity_id']);
        });
    }
    /**
     * Reverse the migrations 
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_schema');
    }
}