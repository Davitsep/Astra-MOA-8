<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Event;
use App\Models\EventPartner;
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

    $topBrandData = [];

    foreach ($topBrands as $brand) {
        $events = $brand->event_partner;

        // Mengelompokkan event berdasarkan kota
        $eventsByCity = $events->groupBy('city');

        // Menghitung jumlah event pada setiap kelompok kota
        $cityEventCounts = $eventsByCity->map(function ($events) {
            return count($events);
        });

        // Mengambil 4 cityEventCounts paling banyak
        $topCityEventCounts = $cityEventCounts->sortByDesc('cityEventCounts')->take(4);

        $brandData = [
            'brand' => $brand->name, // Ubah ini sesuai dengan field yang sesuai
            'topCityEventCounts' => $topCityEventCounts,
        ];

        $topBrandData[] = $brandData;
    }

    return response()->json(['topBrandData' => $topBrandData]);
}


public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'brand_id' => 'required|exists:brands,id',
        ]);

        // Cek apakah event_id dan brand_id sudah ada dalam tabel event_partner
        $existingEventPartner = EventPartner::where('event_id', $data['event_id'])
            ->where('brand_id', $data['brand_id'])
            ->first();

        if ($existingEventPartner) {
            return response()->json(['message' => 'Event Partner already exists'], 400);
        }

        // Jika belum ada, tambahkan Event Partner baru
        $eventPartner = EventPartner::create($data);

        return response()->json(['message' => 'Event partner created successfully'], 201);
    }

}
