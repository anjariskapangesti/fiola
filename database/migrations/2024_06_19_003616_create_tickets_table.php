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
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id', 191)->primary();
            $table->string('no_reg')->unique();
            $table->string('detail_case')->nullable();
            $table->string('category')->nullable();
            $table->string('location')->nullable();
            $table->string('sla')->nullable();
            $table->string('solution')->nullable();

            $table->string('requestor_name');
            $table->string('requestor_phone');
            $table->string('requestor_department');
            
            $table->string('final_status',30)->nullable();
            $table->boolean('is_it_approve')->nullable();
            $table->boolean('is_on_progress')->nullable();
            $table->boolean('is_finish')->nullable();
            $table->boolean('is_confirm')->nullable();
            $table->timestamp('it_approval_date')->nullable();
            $table->timestamp('on_progress_date')->nullable();
            $table->timestamp('finish_date')->nullable();
            $table->text('it_note')->nullable();
            $table->text('on_progress_note')->nullable();
            $table->text('finish_note')->nullable();
            $table->bigInteger('it_approve_by')->nullable();
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
        Schema::dropIfExists('tickets');
    }
};
