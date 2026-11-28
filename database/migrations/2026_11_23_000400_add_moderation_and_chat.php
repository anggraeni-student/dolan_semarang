<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Laporan konten oleh user (review, thread, balasan, destinasi)
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->string('reportable_type');          // App\Models\Review | Thread | ThreadReply | Destination
            $table->unsignedBigInteger('reportable_id');
            $table->index(['reportable_type', 'reportable_id']);
            $table->string('reason');                   // spam | ofensif | tidak-relevan | data-salah | lainnya
            $table->text('note')->nullable();
            $table->string('status')->default('open');   // open | actioned | warned | dismissed
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('handled_at')->nullable();
            $table->timestamps();
            $table->unique(['reporter_id', 'reportable_type', 'reportable_id']); // 1 user = 1 laporan per konten
        });

        // Satu utas chat per user (member) dengan tim admin
        Schema::create('chat_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            $table->unique('user_id');
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('chat_conversations')->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->text('body');
            $table->boolean('is_warning')->default(false); // peringatan resmi dari admin
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_conversations');
        Schema::dropIfExists('reports');
    }
};
