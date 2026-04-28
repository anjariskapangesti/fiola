<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTimelineRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('project_timeline_requests', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('request_project_id');
            $table->unsignedBigInteger('replace_project_id')->nullable();

            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('requested_to')->nullable();

            $table->string('status')->default('pending');
            // pending, approved, rejected, cancelled

            $table->text('message')->nullable();
            $table->timestamp('responded_at')->nullable();

            $table->timestamps();

            $table->foreign('request_project_id')->references('id')->on('form_project')->onDelete('cascade');
            $table->foreign('replace_project_id')->references('id')->on('form_project')->onDelete('set null');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('requested_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_timeline_requests');
    }
}