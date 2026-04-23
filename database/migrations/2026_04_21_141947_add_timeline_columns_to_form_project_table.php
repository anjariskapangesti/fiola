<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimelineColumnsToFormProjectTable extends Migration
{
    public function up()
    {
        Schema::table('form_project', function (Blueprint $table) {
            $table->boolean('is_timeline_active')->default(false);
            $table->integer('timeline_order')->nullable();
        });
    }

    public function down()
    {
        Schema::table('form_project', function (Blueprint $table) {
            $table->dropColumn([
                'is_timeline_active',
                'timeline_order',
            ]);
        });
    }
}