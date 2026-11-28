<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Review extends Model
{
    protected $fillable = [
        'user_id', 'destination_id', 'score_view', 'score_access',
        'score_cleanliness', 'score_facility', 'body',
        'helpful_count', 'photo_count', 'visited_at',
    ];

    protected $casts = ['visited_at' => 'date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    /** Laporan untuk review ini */
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /** ID penulis konten (target peringatan moderator) */
    public function authorId(): int
    {
        return $this->user_id;
    }

    /** Rata-rata skor review ini */
    public function getScoreAttribute(): float
    {
        return round(($this->score_view + $this->score_access + $this->score_cleanliness + $this->score_facility) / 4, 1);
    }
}
