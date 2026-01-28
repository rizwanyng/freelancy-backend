<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('client_id')->nullable();
            $table->uuid('project_id')->nullable();
            $table->string('client_name')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->dateTime('date')->nullable();
            $table->string('status')->default('Pending');
            $table->boolean('is_external')->default(false);
            $table->string('currency')->default('USD');
            $table->boolean('is_gst_enabled')->default(false);
            $table->decimal('gst_percentage', 5, 2)->default(18.0);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            $table->index('user_id');
            $table->index('client_id');
            $table->index('project_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
