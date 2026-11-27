<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Destinasi yang ditambahkan user (kontributor komunitas)
        Schema::table('destinations', function (Blueprint $table) {
            $table->foreignId('created_by')
                ->nullable()
                ->after('is_featured')
                ->constrained('users')
                ->nullOnDelete();
        });

        // Balasan thread komunitas (thread kini bisa diklik & dibalas)
        Schema::create('thread_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thread_replies');

        Schema::table('destinations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
        });
    }
};
