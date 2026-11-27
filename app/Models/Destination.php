<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'price_range',
        'budget_tier',
        'area',
        'best_time',
        'opening_hours',
        'address',
        'description',
        'image_url',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}