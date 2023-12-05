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
        Schema::create('form_project', function (Blueprint $table) {
            $table->id();
            $table->string('no_reg')->unique();
            $table->string('npk', 10)->nullable();
            $table->string('fullname', 100)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('phone',200)->nullable();
            $table->string('nama_project',200)->nullable();
            $table->string('lampiran',200)->nullable();
            $table->text('kondisi_sebelum')->nullable();
            $table->text('kondisi_target')->nullable();            
            $table->text('benefit')->nullable();       
            $table->text('alat')->nullable();    
            $table->string('final_status',30)->nullable();            
            $table->boolean('is_manager_approve')->nullable();
            $table->boolean('is_it_approve')->nullable();
            $table->boolean('is_it_mgr_approve')->nullable();
            $table->boolean('is_delay')->nullable();            
            $table->boolean('is_finish')->nullable();
            $table->boolean('is_confirm')->nullable();
            $table->timestamp('manager_approval_date')->nullable();
            $table->timestamp('it_approval_date')->nullable();
            $table->timestamp('it_mgr_approval_date')->nullable();
            $table->timestamp('delay_date')->nullable();
            $table->timestamp('finish_date')->nullable();
            $table->text('manager_note',100)->nullable();
            $table->text('it_note',100)->nullable();
            $table->text('it_mgr_note',100)->nullable();
            $table->text('delay_note')->nullable();
            $table->text('finish_note',100)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('created_dept')->nullable();
            $table->bigInteger('it_approve_by')->nullable();
            $table->bigInteger('finish_by')->nullable();
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
        Schema::dropIfExists('form_project');
    }
};
