<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            if (!Schema::hasColumn('destinations', 'budget_tier')) {
                $table->string('budget_tier')->nullable();
            }
            if (!Schema::hasColumn('destinations', 'area')) {
                $table->string('area')->nullable();
            }
            if (!Schema::hasColumn('destinations', 'best_time')) {
                $table->string('best_time')->nullable();
            }
            if (!Schema::hasColumn('destinations', 'opening_hours')) {
                $table->string('opening_hours')->nullable();
            }
        });

        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'visit_time_tag')) {
                $table->string('visit_time_tag')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn(['budget_tier', 'area', 'best_time', 'opening_hours']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['visit_time_tag']);
        });
    }
};