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
            $table->text('purpose')->change();
            $table->text('manager_note')->change();
            $table->text('it_note')->change();
            $table->text('it_mgr_note')->change();
            $table->text('delay_note')->change();
            $table->text('finish_note')->change();
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
            $table->string('purpose')->change();
            $table->string('manager_note')->change();
            $table->string('it_note')->change();
            $table->string('it_mgr_note')->change();
            $table->string('delay_note')->change();
            $table->string('finish_note')->change();
        });
    }
};
