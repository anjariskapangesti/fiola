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
        Schema::table('form_account', function (Blueprint $table) {
            $table->boolean('is_delay')->nullable()->after('is_it_mgr_approve');
            $table->timestamp('delay_date')->nullable()->after('it_mgr_approval_date');
            $table->string('delay_note',255)->nullable()->after('it_mgr_note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_account', function (Blueprint $table) {
            //
        });
    }
};
