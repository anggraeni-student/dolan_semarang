<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Collection extends Model
{
    protected $fillable = ['user_id', 'name', 'icon'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(Destination::class);
    }
}
