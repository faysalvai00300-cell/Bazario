<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ip_blocks', function (Blueprint $create) {
            $create->id();
            $create->string('ip_address')->unique();
            $create->string('reason')->nullable();
            $create->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ip_blocks');
    }
};
