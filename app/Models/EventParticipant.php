<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    protected $table = 'event_participant';
    protected $fillable = ['event_id', 'user_id', 'is_attend', 'created_at', 'updated_at'];

    // Relasi dengan tabel Event
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // Relasi dengan tabel User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
