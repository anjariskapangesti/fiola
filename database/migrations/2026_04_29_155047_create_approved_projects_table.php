<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovedProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('approved_projects', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('project_id')->nullable()->unique();

            $table->string('nama')->nullable();
            $table->string('department')->nullable();
            $table->string('hari')->nullable();
            $table->string('bulan')->nullable();
            $table->string('tahun')->nullable();
            $table->string('nama_project')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('approved_projects');
    }
}