<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormNewFolder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_new_folder', function (Blueprint $table) {
            $table->id();
            $table->string('no_reg')->unique();
            $table->string('foldername');
            $table->string('mainpath');
            $table->string('purpose',100)->nullable();
            $table->string('final_status',30)->nullable();
            $table->boolean('is_manager_approve')->nullable();
            $table->boolean('is_it_approve')->nullable();
            $table->boolean('is_it_mgr_approve')->nullable();
            $table->boolean('is_finish')->nullable();
            $table->boolean('is_confirm')->nullable();
            $table->timestamp('manager_approval_date')->nullable();
            $table->timestamp('it_approval_date')->nullable();
            $table->timestamp('it_mgr_approval_date')->nullable();
            $table->timestamp('finish_date')->nullable();
            $table->string('manager_note',100)->nullable();
            $table->string('it_note',100)->nullable();
            $table->string('it_mgr_note',100)->nullable();
            $table->string('finish_note',100)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('created_dept')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_new_folder');
    }
}
