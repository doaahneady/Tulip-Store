<?php

namespace App\Http\Controllers;

use App\Models\Driver;

class DriverTrackingController extends Controller
{
    /**
     * Show the driver tracking page
     */
    public function index()
    {
        $drivers = Driver::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('driver.tracking', compact('drivers'));
    }
}
