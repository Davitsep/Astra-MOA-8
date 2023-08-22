<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventParticipant;

class EventParticipantController extends Controller
{
    // Fungsi untuk menambahkan data ke tabel event_participant
    public function create(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
            'is_attend' => 'required|in:yes,no', // Validasi ENUM 'yes' atau 'no'
        ]);

        $eventParticipant = new EventParticipant();
        $eventParticipant->event_id = $request->event_id;
        $eventParticipant->user_id = $request->user_id;
        $eventParticipant->is_attend = $request->is_attend; // Nilai valid ENUM 'yes' atau 'no'
        $eventParticipant->created_at = now();
        $eventParticipant->updated_at = now();
        $eventParticipant->save();

        return response()->json(['message' => 'Event participant created successfully'], 201);
    }
}
