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
        Schema::create('model_has_job_ranks', function (Blueprint $table) {
            $table->unsignedBigInteger('model_id');
            $table->string('model_type');
            $table->unsignedBigInteger('job_rank_id');

            $table->primary(['model_id', 'model_type', 'job_rank_id']);
            $table->index(['model_id', 'model_type']);

            $table->foreign('model_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('job_rank_id')
                ->references('id')
                ->on('job_ranks')
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
        Schema::dropIfExists('model_has_job_ranks');
    }
};
