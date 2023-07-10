<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubfolderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subfolders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('folder_id');
            $table->string('name', 100);
            $table->timestamps();

            $table->foreign('folder_id')
                ->references('id')
                ->on('folders')
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
        Schema::dropIfExists('subfolders');
    }
}
