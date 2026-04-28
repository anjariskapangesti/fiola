<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultiApprovalToProjectTimelineRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('project_timeline_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_approved_by')->nullable();
            $table->timestamp('owner_approved_at')->nullable();

            $table->unsignedBigInteger('manager_approved_by')->nullable();
            $table->timestamp('manager_approved_at')->nullable();

            $table->unsignedBigInteger('director_approved_by')->nullable();
            $table->timestamp('director_approved_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('project_timeline_requests', function (Blueprint $table) {
            $table->dropColumn([
                'owner_approved_by',
                'owner_approved_at',
                'manager_approved_by',
                'manager_approved_at',
                'director_approved_by',
                'director_approved_at',
            ]);
        });
    }
}