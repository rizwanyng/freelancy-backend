<?php

use Illuminate\Database\Migrations\Migration;
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
        // Remove unwanted features if they exist
        DB::table('feature_settings')
            ->whereIn('key', [
                'biometric_lock',
                // Add any other keys you want to remove here
            ])
            ->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Optionally re-insert them if rolling back, 
        // but typically for cleanup we just leave it or re-seed.
    }
};
