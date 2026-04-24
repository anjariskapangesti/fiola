<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormNewFolderAccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_new_folder_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('new_folder_id');
            $table->string('username');
            $table->string('department');
            $table->string('permission');
            $table->timestamps();

            $table->foreign('new_folder_id')
                  ->references('id')
                  ->on('form_new_folder')
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
        Schema::dropIfExists('form_new_folder_access');
    }
}
