<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Destination extends Model
{
    protected $fillable = [
        'slug', 'name', 'category_id', 'location', 'description', 'hero_image',
        'lat', 'lng', 'htm', 'opening_hours', 'best_time', 'access_info', 'is_featured',
        'created_by',
    ];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** User (kontributor komunitas) yang menambahkan tempat ini */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Laporan untuk destinasi ini */
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /** ID penulis konten (target peringatan moderator) */
    public function authorId(): ?int
    {
        return $this->created_by;
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class);
    }

    /** Rata-rata skor gabungan 4 aspek */
    public function getRatingAttribute(): float
    {
        $agg = $this->reviews()
            ->selectRaw('avg((score_view+score_access+score_cleanliness+score_facility)/4.0) as avg_score, count(*) as total')
            ->first();

        return $agg && $agg->avg_score ? round((float) $agg->avg_score, 1) : 0.0;
    }

    public function getReviewCountAttribute(): int
    {
        return (int) $this->reviews()->count();
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeTopRated(Builder $query): Builder
    {
        return $query->withAvg('reviews', \DB::raw('(score_view+score_access+score_cleanliness+score_facility)/4.0'))
            ->orderByDesc('reviews_avg_' . \DB::raw('(score_view+score_access+score_cleanliness+score_facility)/4.0'));
    }
}
