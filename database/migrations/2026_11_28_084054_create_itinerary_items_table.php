<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itinerary_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerary_id')->constrained('itineraries')->cascadeOnDelete();
            $table->foreignId('destination_id')->constrained('destinations')->cascadeOnDelete();
            $table->time('visit_time')->nullable();         // Jam kunjung, misal 09:00
            $table->unsignedInteger('estimated_cost')->default(0); // Estimasi biaya tambahan (makan/parkir)
            $table->string('notes')->nullable();            // Catatan khusus, misal "Beli lumpia"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itinerary_items');
    }
};