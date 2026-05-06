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
            if (!Schema::hasColumn('settings', 'seo_meta_title')) {
                $table->string('seo_meta_title')->nullable()->after('site_name');
            }
            if (!Schema::hasColumn('settings', 'seo_meta_description')) {
                $table->text('seo_meta_description')->nullable()->after('seo_meta_title');
            }
            if (!Schema::hasColumn('settings', 'seo_meta_keywords')) {
                $table->string('seo_meta_keywords')->nullable()->after('seo_meta_description');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('products', 'meta_keywords')) {
                $table->string('meta_keywords')->nullable()->after('meta_description');
            }
        });

        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('name');
            }
            if (!Schema::hasColumn('categories', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('categories', 'meta_keywords')) {
                $table->string('meta_keywords')->nullable()->after('meta_description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
