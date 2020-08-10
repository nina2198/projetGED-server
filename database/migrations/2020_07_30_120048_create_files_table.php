<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('file_type_id');
            $table->text('path');
            $table->double('file_size');
            $table->unsignedBigInteger('folder_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('folder_id')->references('id')->on('folders')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('file_type_id')->references('id')->on('file_types')
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
        Schema::dropIfExists('files');
    }
}
