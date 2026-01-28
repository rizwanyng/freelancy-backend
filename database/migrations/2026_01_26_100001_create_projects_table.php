<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('client_id')->nullable();
            $table->string('name');
            $table->string('client_name')->nullable();
            $table->decimal('budget', 15, 2)->default(0);
            $table->string('status')->default('Not Started');
            $table->dateTime('deadline')->nullable();
            $table->integer('estimated_hours')->default(0);
            $table->string('currency')->default('USD');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            $table->index('user_id');
            $table->index('client_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
