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
            $table->string('courier_type')->default('steadfast')->after('is_smtp_active');
            $table->string('steadfast_api_key')->nullable()->after('courier_type');
            $table->string('steadfast_secret_key')->nullable()->after('steadfast_api_key');
            $table->string('redx_api_token')->nullable()->after('steadfast_secret_key');
            $table->string('pathao_client_id')->nullable()->after('redx_api_token');
            $table->string('pathao_client_secret')->nullable()->after('pathao_client_id');
            $table->string('pathao_username')->nullable()->after('pathao_client_secret');
            $table->string('pathao_password')->nullable()->after('pathao_username');
            $table->string('pathao_store_id')->nullable()->after('pathao_password');
            $table->boolean('pathao_is_test')->default(true)->after('pathao_store_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'courier_type',
                'steadfast_api_key',
                'steadfast_secret_key',
                'redx_api_token',
                'pathao_client_id',
                'pathao_client_secret',
                'pathao_username',
                'pathao_password',
                'pathao_store_id',
                'pathao_is_test'
            ]);
        });
    }
};
