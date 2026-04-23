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
        Schema::table('form_project', function (Blueprint $table) {
            $table->boolean('is_reschedule')->default(0)->after('alat');
            $table->unsignedBigInteger('reschedule_target_id')->nullable()->after('is_reschedule');
            $table->boolean('is_dir_approve')->nullable()->after('it_mgr_approve_by');
            $table->bigInteger('dir_approve_by')->nullable()->after('is_dir_approve');
            $table->timestamp('dir_approval_date')->nullable()->after('dir_approve_by');
            $table->text('dir_note')->nullable()->after('dir_approval_date');
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
            $table->dropColumn([
                'is_reschedule',
                'reschedule_target_id',
                'is_dir_approve',
                'dir_approve_by',
                'dir_approval_date',
                'dir_note'
            ]);
        });
    }
};
