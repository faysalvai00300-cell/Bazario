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
                $table->decimal('delivery_charge', 10, 2)->default(60.00)->after('facebook_pixel_id');
            }
            if (!Schema::hasColumn('settings', 'facebook_page_link')) {
                $table->string('facebook_page_link')->nullable()->after('delivery_charge');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['delivery_charge', 'facebook_page_link']);
        });
    }
};
