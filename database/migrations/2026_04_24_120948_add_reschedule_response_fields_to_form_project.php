<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     *  @return void
     */
    public function up()
    {
        Schema::table('form_project', function (Blueprint $table) {
            $table->string('target_response', 20)->default('pending')->nullable(); // pending, yes, no
            $table->timestamp('target_response_date')->nullable();
            $table->timestamp('target_reschedule_start_date')->nullable();
            $table->timestamp('target_reschedule_end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_project', function (Blueprint $table) {
            $table->dropColumn(['target_response', 'target_response_date', 'target_reschedule_start_date', 'target_reschedule_end_date']);
        });
    }
};
