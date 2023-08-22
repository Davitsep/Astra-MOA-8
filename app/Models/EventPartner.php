<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPartner extends Model
{
    protected $table = 'event_partner';

    protected $fillable = [
        'event_id', 'brand_id'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}