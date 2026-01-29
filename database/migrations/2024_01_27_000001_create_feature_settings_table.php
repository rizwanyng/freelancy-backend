<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->boolean('is_enabled')->default(false);
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed default features
        $features = [
             
             // Subscription & Payment
            ['key' => 'subscription_limits', 'label' => 'Subscription Tiers/Limits', 'is_enabled' => true, 'category' => 'Monetization', 'description' => 'Limit free users to 3 projects/clients.'],
            ['key' => 'premium_proposals', 'label' => 'Premium AI Proposals', 'is_enabled' => true, 'category' => 'Monetization', 'description' => 'Add professional AI-powered PDF proposal templates.'],
            ['key' => 'payment_gateway', 'label' => 'Direct Payments (Stripe/PayPal)', 'is_enabled' => true, 'category' => 'Monetization', 'description' => 'Allow users to accept payments in app.'],
            // ['key' => 'ads_integration', 'label' => 'Ad Integration', 'is_enabled' => true, 'category' => 'Monetization', 'description' => 'Show ads/tips to free users.'],

            // AI & Smart Tools
            ['key' => 'smart_chat_assistant', 'label' => 'AI Smart Chat Assistant', 'is_enabled' => true, 'category' => 'AI', 'description' => 'Chatbot for billing reminders and help.'],
            // ['key' => 'ai_proposal_enhancer', 'label' => 'AI Proposal Enhancer', 'is_enabled' => true, 'category' => 'AI', 'description' => 'AI rewriting for professional proposals.'],
            ['key' => 'pdf_invoice_download', 'label' => 'Branded PDF Invoices', 'is_enabled' => true, 'category' => 'Tools', 'description' => 'Download invoices as premium PDFs.'],
            ['key' => 'auto_email_invoice', 'label' => 'Auto-Email Invoices', 'is_enabled' => true, 'category' => 'Tools', 'description' => 'Automatically email PDFs to clients.'],

            // Utils
            ['key' => 'multi_currency_auto', 'label' => 'Auto-Update Currency Rates', 'is_enabled' => true, 'category' => 'Tools', 'description' => 'Live exchange rates.'],
            ['key' => 'client_portal', 'label' => 'Client Portal Links', 'is_enabled' => true, 'category' => 'Tools', 'description' => 'Web links for clients to track/pay.'],
            
            // Teams & Sync
            ['key' => 'team_access', 'label' => 'Team/Partner Access', 'is_enabled' => true, 'category' => 'Experience', 'description' => 'Invite collaborators to projects.'],
            ['key' => 'calendar_sync', 'label' => 'Calendar Sync', 'is_enabled' => true, 'category' => 'Experience', 'description' => 'Sync deadlines to Google/iCal.'],
            ['key' => 'push_notifications', 'label' => 'Push Notifications', 'is_enabled' => true, 'category' => 'Experience', 'description' => 'Alerts and timer reminders.'],

            // Security
             // ['key' => 'biometric_lock', 'label' => 'Biometric Lock', 'is_enabled' => true, 'category' => 'Security', 'description' => 'FaceID/Fingerprint support.'],
            ['key' => 'cloud_backup_csv', 'label' => 'Daily Cloud Backup (CSV)', 'is_enabled' => true, 'category' => 'Security', 'description' => 'Daily data exports for safety.'],
        ];

        DB::table('feature_settings')->insert($features);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feature_settings');
    }
};
