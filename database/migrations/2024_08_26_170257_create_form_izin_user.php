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
        Schema::create('form_izin_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('izin_id');
            $table->string('npk');
            $table->string('name');
            $table->string('asal_perusahaan');
            $table->string('no_hp');
            $table->timestamps();

            $table->foreign('izin_id')
                    ->references('id')
                    ->on('form_izin')
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
        Schema::dropIfExists('form_izin_user');
    }
};
