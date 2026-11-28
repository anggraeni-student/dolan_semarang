<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
=======
>>>>>>> d86df827b9e5ffa48123c8563af38becbc92aa26

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'user_name',
        'rating',
        'visit_time_tag',
        'comment',
        'photo_url',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
<<<<<<< HEAD

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
=======
}
>>>>>>> d86df827b9e5ffa48123c8563af38becbc92aa26
