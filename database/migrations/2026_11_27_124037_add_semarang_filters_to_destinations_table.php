<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            if (!Schema::hasColumn('destinations', 'budget_range')) {
                $table->string('budget_range')->nullable();
            }
            if (!Schema::hasColumn('destinations', 'area')) {
                $table->string('area')->nullable();
            }
            if (!Schema::hasColumn('destinations', 'best_time')) {
                $table->string('best_time')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $cols = array_filter(['budget_range', 'area'], fn ($c) => Schema::hasColumn('destinations', $c));
            if ($cols) {
                $table->dropColumn($cols);
            }
        });
    }
};