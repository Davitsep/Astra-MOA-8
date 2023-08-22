<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Digital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DigitalController extends Controller
{
    public function getEventDetails()
    {
        $eventDetails = DB::table('events')
        ->join('event_types', 'events.event_type_id', '=', 'event_types.id')
        ->select(
            'events.id as event_id',
            DB::raw('CONCAT(event_types.name, " / ", events.city) as event_info'),
            DB::raw('COUNT(event_participant.event_id) as total_traffic'),
            DB::raw('SUM(CASE WHEN event_participant.is_attend = "yes" THEN 1 ELSE 0 END) as total_interaction'),
            DB::raw('ROUND(SUM(TIME_TO_SEC(TIMEDIFF(events.end_date, events.start_date))) / (3600 * 24)) as time_on_site_days')
        )
        ->leftJoin('event_participant', 'events.id', '=', 'event_participant.event_id')
        ->groupBy('events.id', 'event_types.name', 'events.city')
        ->get();

        foreach ($eventDetails as $eventDetail) {
            $digitalData = [
                'event_id' => $eventDetail->event_id,
                'total_traffic' => $eventDetail->total_traffic,
                'interaction' => $eventDetail->total_interaction,
                'time_on_site' => $eventDetail->time_on_site_days,
            ];

            $existingDigital = Digital::where('event_id', $digitalData['event_id'])->first();

            if ($existingDigital) {
                if (
                    $existingDigital->total_traffic != $digitalData['total_traffic'] ||
                    $existingDigital->interaction != $digitalData['interaction'] ||
                    $existingDigital->time_on_site != $digitalData['time_on_site']
                ) {
                    $existingDigital->update($digitalData);
                }
            } else {
                Digital::create($digitalData);
            }
        }

        return response()->json($eventDetails);
    }

    public function filter(Request $request)
    {
        $type = $request->input('type');
        $year = $request->input('year');
        $month = $request->input('month');

        // Query untuk mendapatkan data dengan filter yang diberikan
        $filteredEventDetailsQuery = DB::table('events')
            ->join('event_types', 'events.event_type_id', '=', 'event_types.id')
            ->select(
                'events.id as event_id',
                DB::raw('CONCAT(event_types.name, " / ", events.city) as event_info'),
                DB::raw('COUNT(event_participant.event_id) as total_traffic'),
                DB::raw('SUM(CASE WHEN event_participant.is_attend = 1 THEN 1 ELSE 0 END) as total_interaction'),
                DB::raw('ROUND(SUM(TIME_TO_SEC(TIMEDIFF(events.end_date, events.start_date))) / (3600 * 24)) as time_on_site_days')
            )
            ->leftJoin('event_participant', 'events.id', '=', 'event_participant.event_id')
            ->groupBy('events.id', 'event_types.name', 'events.city');

        if ($type) {
            $filteredEventDetailsQuery->where('event_types.name', $type);
        }

        if ($year) {
            $filteredEventDetailsQuery->whereYear('events.start_date', $year);
        }

        if ($month) {
            $filteredEventDetailsQuery->whereMonth('events.start_date', $month);
        }

        $filteredEventDetails = $filteredEventDetailsQuery->get();

        return response()->json($filteredEventDetails);
    }

    public function getTopEvents()
    {
        $topEventsByTraffic = Digital::orderBy('total_traffic', 'desc')
            ->take(4)
            ->get();

        $topEventsByInteraction = Digital::orderBy('interaction', 'desc')
            ->take(4)
            ->get();

        $topEventsByTimeOnSite = Digital::orderBy('time_on_site', 'desc')
            ->take(4)
            ->get();

        return response()->json([
            'top_events_by_traffic' => $topEventsByTraffic,
            'top_events_by_interaction' => $topEventsByInteraction,
            'top_events_by_time_on_site' => $topEventsByTimeOnSite,
        ]);
    }
}

