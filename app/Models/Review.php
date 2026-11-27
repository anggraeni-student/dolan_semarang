<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}