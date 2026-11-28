<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'trip_date',
    ];

    protected $casts = [
        'trip_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ItineraryItem::class)->orderBy('visit_time');
    }

    public function getTotalCostAttribute()
    {
        return $this->items->sum(function ($item) {
            return ($item->destination->htm ?? 0) + ($item->estimated_cost ?? 0);
        });
    }
}