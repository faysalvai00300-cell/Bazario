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
            if (!Schema::hasColumn('settings', 'twitter_link')) {
                $table->string('twitter_link')->nullable()->after('facebook_page_link');
            }
            if (!Schema::hasColumn('settings', 'instagram_link')) {
                $table->string('instagram_link')->nullable()->after('twitter_link');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['twitter_link', 'instagram_link']);
        });
    }
};
