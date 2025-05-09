<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function cities($provinceId)
    {
        $cities = \App\Models\City::where('province_id', $provinceId)->get(['id', 'name']);
        return response()->json($cities->map(function($city){
            return ['id' => $city->id, 'text' => $city->name];
        }));
    }
}
