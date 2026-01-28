<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('project_id');
            $table->string('title');
            $table->boolean('is_completed')->default(false);
            $table->integer('total_seconds')->default(0);
            $table->boolean('is_running')->default(false);
            $table->bigInteger('last_start_time')->nullable();
            $table->json('daily_tracked')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->index('user_id');
            $table->index('project_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
