<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverTrackingController extends Controller
{
    /**
     * Show the driver tracking page
     */
    public function index()
    {
        $drivers = Driver::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('driver.tracking', compact('drivers'));
    }
}
