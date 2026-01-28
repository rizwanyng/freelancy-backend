<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_link')->nullable()->after('plan');
            $table->string('paypal_email')->nullable()->after('stripe_link');
            $table->string('upi_id')->nullable()->after('paypal_email'); // Common in India
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['stripe_link', 'paypal_email', 'upi_id']);
        });
    }
};
