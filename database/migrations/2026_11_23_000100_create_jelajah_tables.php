<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kategori destinasi (nav atas): Alam, Pantai, Gunung, ...
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('icon')->nullable();      // nama Material Icon
            $table->integer('position')->default(0);
            $table->timestamps();
        });

        // Destinasi wisata
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('location');              // "Papua Barat Daya"
            $table->text('description');
            $table->string('hero_image');
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->unsignedInteger('htm')->default(0);        // harga tiket masuk (Rp)
            $table->string('opening_hours')->nullable();
            $table->string('best_time')->nullable();
            $table->string('access_info')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        // Review komunitas
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('score_view')->default(5);      // pemandangan
            $table->unsignedTinyInteger('score_access')->default(5);    // akses
            $table->unsignedTinyInteger('score_cleanliness')->default(5); // kebersihan
            $table->unsignedTinyInteger('score_facility')->default(5);  // fasilitas
            $table->text('body');
            $table->unsignedInteger('helpful_count')->default(0);
            $table->unsignedInteger('photo_count')->default(0);
            $table->date('visited_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'destination_id']); // satu review per user per destinasi
        });

        // Artikel panduan editorial
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('tag');                    // ITINERARY / TIPS / SEJARAH / ...
            $table->string('cover_image');
            $table->text('excerpt');
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('read_minutes')->default(5);
            $table->unsignedInteger('likes')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        // Thread komunitas
        Schema::create('threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('tanya');       // tanya | cerita
            $table->string('title');
            $table->text('body')->nullable();
            $table->unsignedInteger('reply_count')->default(0);
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
        });

        // Minat user (onboarding)
        Schema::create('interests', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('icon')->nullable();
        });

        Schema::create('interest_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('interest_id')->constrained()->cascadeOnDelete();
            $table->primary(['user_id', 'interest_id']);
        });

        // Koleksi pribadi (wishlist, sudah dikunjungi, koleksi custom)
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('collection_destination', function (Blueprint $table) {
            $table->foreignId('collection_id')->constrained()->cascadeOnDelete();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->primary(['collection_id', 'destination_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_destination');
        Schema::dropIfExists('collections');
        Schema::dropIfExists('interest_user');
        Schema::dropIfExists('interests');
        Schema::dropIfExists('threads');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('destinations');
        Schema::dropIfExists('categories');
    }
};
