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
        Schema::table('form_hardware', function (Blueprint $table) {
            $table->text('purpose')->change();
            $table->text('manager_note')->change();
            $table->text('it_note')->change();
            $table->text('it_mgr_note')->change();
            $table->text('finish_note')->change();
            $table->bigInteger('it_approve_by')->nullable()->after('created_dept');
            $table->bigInteger('finish_by')->nullable()->after('it_approve_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_hardware', function (Blueprint $table) {
            //
        });
    }
};
