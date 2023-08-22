<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Event;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();

        return response()->json($brands);
    }

    public function show($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['message' => 'Brand tidak ditemukan!'], 404);
        }

        return response()->json($brand);
    }

    public function store(Request $request)
    {
        // Validasi input disini jika diperlukan
        $brand = new Brand();
        $brand->name = $request->input('name');
        $brand->type = $request->input('type');
        $brand->result = $request->input('result');
        $brand->description = $request->input('description');
        $brand->payment_method = $request->input('payment_method');
        $brand->save();

        return response()->json([
            'message' => 'Merek berhasil ditambahkan'
        ]);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['message' => 'Merek tidak ditemukan!'], 404);
        }

        // Validasi input disini jika diperlukan
        $brand->name = $request->input('name');
        $brand->type = $request->input('type');
        $brand->result = $request->input('result');
        $brand->description = $request->input('description');
        $brand->payment_method = $request->input('payment_method');
        $brand->save();

        return response()->json([
            'message' => 'Merek berhasil diperbarui'
        ]);
    }

    public function incrementResult($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['message' => 'Brand tidak ditemukan!'], 404);
        }

        // Menambahkan nilai 1 pada kolom "result"
        $brand->increment('result', 1);

        return response()->json([
            'message' => 'Nilai kolom "result" berhasil ditambahkan'
        ]);
    }

     public function destroy($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['message' => 'Merek tidak ditemukan!'], 404);
        }

        $brand->delete();

        return response()->json(['message' => 'Merek berhasil dihapus']);
    }
    

    public function filter(Request $request)
    {
    $type = $request->input('type');
    $year = $request->input('year');
    $month = $request->input('month');

    $filteredBrands = Brand::when($type, function ($query, $type) {
                           return $query->where('type', $type);
                       })
                       ->when($year, function ($query, $year) {
                           return $query->whereYear('created_at', $year);
                       })
                       ->when($month, function ($query, $month) {
                           return $query->whereMonth('created_at', $month);
                       })
                       ->get();

    return response()->json(['filteredBrands' => $filteredBrands]);
    }



}
