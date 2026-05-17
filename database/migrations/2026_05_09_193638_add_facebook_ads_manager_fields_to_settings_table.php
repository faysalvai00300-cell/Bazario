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
            $table->string('facebook_app_id')->nullable()->after('facebook_access_token');
            $table->string('facebook_app_secret')->nullable()->after('facebook_app_id');
            $table->string('facebook_ad_account_id')->nullable()->after('facebook_app_secret');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['facebook_app_id', 'facebook_app_secret', 'facebook_ad_account_id']);
        });
    }
};
