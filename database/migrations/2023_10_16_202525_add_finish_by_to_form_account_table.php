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
        Schema::table('form_account', function (Blueprint $table) {
            //
        });
    }
};
