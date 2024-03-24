<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_folder_access_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('folder_access_id');
            $table->string('username');
            $table->string('department');
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
        Schema::dropIfExists('form_folder_access_user');
    }
};
