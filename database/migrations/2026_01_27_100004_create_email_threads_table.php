<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('email_threads');
        Schema::create('email_threads', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->string('from_email');
            $table->string('from_name');
            $table->string('to_email')->nullable();
            $table->string('client_id')->nullable();
            $table->string('project_id')->nullable();
            $table->string('invoice_id')->nullable();
            $table->text('snippet');
            $table->timestamp('received_at');
            $table->boolean('is_read')->default(false);
            $table->boolean('is_important')->default(false);
            $table->json('labels')->nullable();
            $table->string('uid')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_threads');
    }
};
