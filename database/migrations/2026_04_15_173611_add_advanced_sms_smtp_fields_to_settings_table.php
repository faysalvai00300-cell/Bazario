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
            $table->string('sms_api_key')->nullable()->after('sms_api_url');
            $table->string('sms_sender_id')->nullable()->after('sms_api_key');
            
            $table->boolean('is_smtp_active')->default(false)->after('is_sms_active');
            $table->string('smtp_host')->nullable()->after('is_smtp_active');
            $table->string('smtp_port')->nullable()->after('smtp_host');
            $table->string('smtp_username')->nullable()->after('smtp_port');
            $table->string('smtp_password')->nullable()->after('smtp_username');
            $table->string('smtp_encryption')->default('tls')->nullable()->after('smtp_password');
            $table->string('smtp_from_address')->nullable()->after('smtp_encryption');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'sms_api_key', 'sms_sender_id',
                'is_smtp_active', 'smtp_host', 'smtp_port',
                'smtp_username', 'smtp_password', 'smtp_encryption', 'smtp_from_address'
            ]);
        });
    }
};
