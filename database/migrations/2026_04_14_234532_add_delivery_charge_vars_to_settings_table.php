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
            $table->decimal('delivery_charge_inside', 10, 2)->default(70.00)->after('delivery_charge');
            $table->decimal('delivery_charge_outside', 10, 2)->default(130.00)->after('delivery_charge_inside');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['delivery_charge_inside', 'delivery_charge_outside']);
        });
    }
};
