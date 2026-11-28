<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Thread extends Model
{
    protected $fillable = ['user_id', 'type', 'title', 'body', 'reply_count', 'last_activity_at'];

    protected $casts = ['last_activity_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ThreadReply::class);
    }

    /** Laporan untuk thread ini */
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /** ID penulis konten (target peringatan moderator) */
    public function authorId(): int
    {
        return $this->user_id;
    }
}
