<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\City;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function cities(Request $request, $province_id)
    {
        $cities = City::where('province_id', $province_id)
            ->orderBy('name')
            ->get();

        return response()->json($cities);
    }
}
