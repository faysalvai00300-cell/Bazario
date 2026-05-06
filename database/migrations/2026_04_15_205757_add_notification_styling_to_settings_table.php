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
            $table->string('notification_bg_color')->default('#1e1e2d')->after('model_notification');
            $table->string('notification_text_color')->default('#ffffff')->after('notification_bg_color');
            $table->string('notification_effect')->default('shine')->after('notification_text_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['notification_bg_color', 'notification_text_color', 'notification_effect']);
        });
    }
};
