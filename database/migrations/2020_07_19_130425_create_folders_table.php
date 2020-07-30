<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name')->unique();
            $table->text('description');
            $table->enum('status', ['ACCEPTED', 'PENDING', 'REJECTED', 'ARCHIVED'])->default('PENDING');
            $table->integer('track_id')->unique();
            $table->date('archinving_date')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('folder_type_id');
            $table->unsignedBigInteger('archive_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('folder_type_id')->references('id')->on('folder_types')
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
        Schema::dropIfExists('folder');
    }
}
