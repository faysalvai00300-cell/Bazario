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
            if (!Schema::hasColumn('settings', 'delivery_charge')) {
                $table->decimal('delivery_charge', 10, 2)->default(60);
            }
            if (!Schema::hasColumn('settings', 'free_delivery_threshold')) {
                $table->decimal('free_delivery_threshold', 10, 2)->default(1000);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['delivery_charge', 'free_delivery_threshold']);
        });
    }
};
