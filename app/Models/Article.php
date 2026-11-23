<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    protected $fillable = [
        'slug', 'title', 'tag', 'cover_image', 'excerpt',
        'author_id', 'read_minutes', 'likes', 'is_featured',
    ];

    protected $casts = ['is_featured' => 'boolean'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
