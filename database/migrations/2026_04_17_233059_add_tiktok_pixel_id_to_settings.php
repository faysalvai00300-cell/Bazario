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
            $table->string('tiktok_pixel_id')->nullable()->after('facebook_access_token');
            $table->text('tiktok_access_token')->nullable()->after('tiktok_pixel_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['tiktok_pixel_id', 'tiktok_access_token']);
        });
    }
};
