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
        // 1. Drop the table with the wrong schema (Integer user_id)
        Schema::dropIfExists('admin_notifications');

        // 2. Re-create the table with the correct schema (UUID user_id)
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            // This creates a CHAR(36) column for UUIDs
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('body');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_notifications');
    }
};
