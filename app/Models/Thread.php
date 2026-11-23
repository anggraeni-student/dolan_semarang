<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Thread extends Model
{
    protected $fillable = ['user_id', 'type', 'title', 'body', 'reply_count', 'last_activity_at'];

    protected $casts = ['last_activity_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
