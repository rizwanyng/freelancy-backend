<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->string('id')->primary();
            // users.id is a UUID, so match the type and FK accordingly
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('source')->default('Website'); // Website, Referral, LinkedIn, etc.
            $table->string('status')->default('new'); // new, contacted, qualified, proposal_sent, won, lost
            $table->decimal('estimated_value', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('last_contact_date')->nullable();
            $table->string('uid')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leads');
    }
};
