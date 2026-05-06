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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('user_agent', 500)->nullable();
            $table->string('path')->nullable();
            $table->date('visit_date');
            $table->timestamp('last_active_at');
            $table->timestamps();

            $table->unique(['ip_address', 'visit_date', 'user_agent'], 'unique_daily_visitor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
