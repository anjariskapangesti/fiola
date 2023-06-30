<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormFolderAccessPath extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_folder_access_path', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('folder_access_id');
            $table->string('folder');
            $table->string('subfolder');
            $table->string('permission');
            $table->timestamps();

            $table->foreign('folder_access_id')
                  ->references('id')
                  ->on('form_folder_access')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_folder_access_path');
    }
}
