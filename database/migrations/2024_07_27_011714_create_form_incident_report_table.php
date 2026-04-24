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
        Schema::create('form_incident_report', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable();
            $table->string('no_reg')->unique();
            $table->string('kategori')->nullable();
            $table->string('penyebab')->nullable();
            $table->string('aktual_keparahan')->nullable();
            $table->string('penemu')->nullable();
            $table->string('department')->nullable();
            $table->timestamp('tanggal_penemuan')->nullable();
            $table->string('device')->nullable();
            $table->text('dampak_awal')->nullable();

            $table->text('kronologi')->nullable();
            $table->text('dampak_luas')->nullable();
            $table->text('root_cause')->nullable();
            $table->text('potensi_kelemahan')->nullable();

            $table->string('staff_corrective_action')->nullable();
            $table->date('tanggal_mulai_corrective_action')->nullable();
            $table->date('tanggal_berakhir_corrective_action')->nullable();
            $table->text('corrective_action')->nullable();
            $table->text('dampak_lanjutan_corrective_action')->nullable();
            $table->string('kondisi_bisnis_corrective_action')->nullable();

            $table->string('staff_preventive_action')->nullable();
            $table->date('tanggal_mulai_preventive_action')->nullable();
            $table->date('tanggal_berakhir_preventive_action')->nullable();
            $table->text('preventive_action')->nullable();
            $table->text('dampak_lanjutan_preventive_action')->nullable();
            $table->string('kondisi_bisnis_preventive_action')->nullable();
            
            $table->string('final_status',30)->nullable();
            $table->boolean('is_manager_approve')->nullable();
            $table->boolean('is_gm_approve')->nullable();
            $table->boolean('is_pres_approve')->nullable();
            $table->boolean('is_it_approve')->nullable();
            $table->boolean('is_it_mgr_approve')->nullable();
            $table->boolean('is_on_progress')->nullable();
            $table->boolean('is_finish')->nullable();
            $table->boolean('is_confirm')->nullable();
            $table->timestamp('manager_approval_date')->nullable();
            $table->timestamp('gm_approval_date')->nullable();
            $table->timestamp('pres_approval_date')->nullable();
            $table->timestamp('it_approval_date')->nullable();
            $table->timestamp('it_mgr_approval_date')->nullable();
            $table->timestamp('on_progress_date')->nullable();
            $table->timestamp('finish_date')->nullable();
            $table->text('manager_note')->nullable();
            $table->text('gm_note')->nullable();
            $table->text('pres_note')->nullable();
            $table->text('it_note')->nullable();
            $table->text('it_mgr_note')->nullable();
            $table->text('on_progress_note')->nullable();
            $table->text('finish_note')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('created_dept')->nullable();
            $table->bigInteger('manager_approve_by')->nullable();
            $table->bigInteger('gm_approve_by')->nullable();
            $table->bigInteger('pres_approve_by')->nullable();
            $table->bigInteger('it_approve_by')->nullable();
            $table->bigInteger('it_mgr_approve_by')->nullable();
            $table->bigInteger('on_progress_by')->nullable();
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
        Schema::dropIfExists('form_incident_report');
    }
};
