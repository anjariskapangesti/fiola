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
        Schema::create('form_it_needs_software', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('it_needs_id');
            $table->string('kebutuhan');
            $table->integer('jumlah');
            $table->date('gr');
            $table->text('tujuan');
            $table->timestamps();

            $table->foreign('it_needs_id')
                    ->references('id')
                    ->on('form_it_needs')
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
        Schema::dropIfExists('form_it_needs_software');
    }
};
