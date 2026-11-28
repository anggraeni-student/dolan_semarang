<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatConversation extends Model
{
    protected $fillable = ['user_id', 'last_message_at'];

    protected $casts = ['last_message_at' => 'datetime'];

    /** Member pemilik utas chat ini */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id');
    }

    public function latestMessage(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id')->latestOfMany();
    }

    /** Jumlah pesan yang belum dibaca oleh $viewer di utas ini */
    public function unreadCountFor(User $viewer): int
    {
        $query = $this->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', $viewer->id);

        // admin hanya perlu membaca pesan dari member (pesan admin lain bukan tugas dia)
        if ($viewer->is_admin) {
            $query->where('sender_id', $this->user_id);
        }

        return (int) $query->count();
    }

    /** Tandai semua pesan dari lawan sebagai sudah dibaca */
    public function markReadFor(User $viewer): void
    {
        $this->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', $viewer->id)
            ->when($viewer->is_admin, fn ($q) => $q->where('sender_id', $this->user_id))
            ->update(['read_at' => now()]);
    }
}
