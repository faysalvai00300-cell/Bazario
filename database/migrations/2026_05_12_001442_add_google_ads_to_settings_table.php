<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('google_ads_client_id')->nullable();
            $table->string('google_ads_client_secret')->nullable();
            $table->string('google_ads_developer_token')->nullable();
            $table->string('google_ads_manager_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'google_ads_client_id',
                'google_ads_client_secret',
                'google_ads_developer_token',
                'google_ads_manager_id'
            ]);
        });
    }
};
