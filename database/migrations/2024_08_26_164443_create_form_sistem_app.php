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
        Schema::create('form_sistem_app', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sistem_id');
            $table->string('app_name');
            $table->timestamps();

            $table->foreign('sistem_id')
                    ->references('id')
                    ->on('form_sistem')
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
        Schema::dropIfExists('form_sistem_app');
    }
};
