<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('destination_id')->constrained('destinations')->cascadeOnDelete();
            $table->timestamps();

            // Mencegah 1 user menyimpan destinasi yang sama berkali-kali
            $table->unique(['user_id', 'destination_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};