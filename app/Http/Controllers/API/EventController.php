<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function event()
    {
        $events = Event::with(['eventType' => function ($query) {
            $query->select('id', 'name');
        }])
        ->get([
            'id', 'event_type_id', 'venue', 'city', 'start_date', 'end_date',
            'games', 'nv', 'visitors', 'spk', 'live_data'
        ]);

        return response()->json($events);
    }
    public function getById($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event tidak ditemukan!'], 404);
        }

        return response()->json($event);
    }
    public function create(Request $request)
    {
        $event = new Event();
        $event->event_type_id = $request->input('event_type_id');
        $event->venue = $request->input('venue');
        $event->city = $request->input('city');
        $event->games = $request->input('games');
        $event->nv = $request->input('nv');
        $event->visitors = $request->input('visitors');
        $event->spk = $request->input('spk');
        $event->live_data = $request->input('live_data');
        $event->start_date = $request->input('start_date');
        $event->end_date = $request->input('end_date');
        $event->save();

        return response()->json([
            "message" => "Berhasil menambahkan event"
        ]);
    }
    public function update(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event tidak ditemukan!'], 404);
        }

        $event->event_type_id = $request->input('event_type_id');
        $event->venue = $request->input('venue');
        $event->city = $request->input('city');
        $event->games = $request->input('games');
        $event->nv = $request->input('nv');
        $event->visitors = $request->input('visitors');
        $event->spk = $request->input('spk');
        $event->live_data = $request->input('live_data');
        $event->start_date = $request->input('start_date');
        $event->end_date = $request->input('end_date');
        $event->save();

        return response()->json([
            "message" => "Berhasil mengupdate event"
        ]);
    }
    public function delete($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event tidak ditemukan!'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Berhasil mendelete event']);
    }
    public function filter(Request $request)
{
    $month = $request->input('month');
    $year = $request->input('year');
    $eventType = $request->input('name');

    $eventsQuery = Event::with('eventType')
        ->when($month, function ($query, $month) {
            return $query->whereMonth('start_date', $month);
        })
        ->when($year, function ($query, $year) {
            return $query->whereYear('start_date', $year);
        })
        ->when($eventType, function ($query, $eventType) {
            return $query->whereHas('eventType', function ($query) use ($eventType) {
                return $query->where('name', $eventType);
            });
        })
        ->get([
            'id', 'event_type_id', 'venue', 'city', 'games', 'nv', 'visitors', 'spk', 'start_date', 'end_date', 'live_data'
        ]);

    return response()->json(['events' => $eventsQuery]);
}

public function eventsDigital(Request $request)
{
    $month = $request->input('month');
    $year = $request->input('year');
    $eventType = $request->input('name');

    $eventsQuery = Event::with('eventType')
        ->where('venue', '=', 'online') // Menambahkan kondisi venue = online
        ->when($month, function ($query, $month) {
            return $query->whereMonth('start_date', $month);
        })
        ->when($year, function ($query, $year) {
            return $query->whereYear('start_date', $year);
        })
        ->when($eventType, function ($query, $eventType) {
            return $query->whereHas('eventType', function ($query) use ($eventType) {
                return $query->where('name', $eventType);
            });
        })
        ->get([
            'id', 'event_type_id', 'venue', 'city', 'games', 'nv', 'visitors', 'spk', 'start_date', 'end_date', 'live_data'
        ]);

    return response()->json(['events' => $eventsQuery]);
}


    
}