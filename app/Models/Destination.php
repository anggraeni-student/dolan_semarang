<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'price_range',
        'description',
        'address',
        'image_url',
    ];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
