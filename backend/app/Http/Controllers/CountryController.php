<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q', ''));
        $query = Country::query();
        if ($q !== '') {
            $query->where(function($w) use ($q){
                $w->where('name','like',"%$q%")
                  ->orWhere('iso2','like',"%$q%")
                  ->orWhere('dial_code','like',"%$q%");
            });
        }
        return response()->json(
            $query->orderBy('name')->get(['name','iso2','dial_code'])
        );
    }
}
