<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class EventPartnerController extends Controller
{
    public function getTopEventData()
    {
        $topBrands = Brand::withCount('event_partner')
            ->orderBy('event_partner_count', 'desc') // Mengurutkan brand berdasarkan jumlah event terbanyak
            ->take(3) // Mengambil 3 brand dengan event terbanyak
            ->get();

        $brandsWithEvents = [];

        foreach ($topBrands as $brand) {
            $events = $brand->event_partner;
            $brandWithEvents = [
                'brand' => $brand,
                'events' => $events,
            ];
            $brandsWithEvents[] = $brandWithEvents;
        }

        return response()->json(['topBrandsWithEvents' => $brandsWithEvents]);
    }

    public function getTopEventsWithSameCity()
{
    $topBrands = Brand::withCount('event_partner')
        ->orderBy('event_partner_count', 'desc')
        ->take(3)
        ->get();

    $eventsWithSameCity = [];

    foreach ($topBrands as $brand) {
        $events = $brand->event_partner;
        
        // Mengelompokkan event berdasarkan kota
        $eventsByCity = $events->groupBy('city');

        // Mengambil 4 event dengan kota yang sama paling banyak
        $topEvents = $eventsByCity->map(function ($events) {
            return $events->take(4)->all();
        });

        // Menghitung jumlah event pada setiap kelompok kota
        $cityEventCounts = $eventsByCity->map(function ($events) {
            return count($events);
        });

        $brandWithEvents = [
            'brand' => $brand,
            'topEventsWithSameCity' => $topEvents,
            'cityEventCounts' => $cityEventCounts,
        ];

        $eventsWithSameCity[] = $brandWithEvents;
    }

    return response()->json(['topEventsWithSameCity' => $eventsWithSameCity]);
}

}
