<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DriverLocationController extends Controller
{
    /**
     * Update driver location from mobile phone GPS
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|exists:drivers,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'speed' => 'nullable|numeric|min:0',
            'accuracy' => 'nullable|numeric|min:0',
            'phone' => 'nullable|string', // For verification
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $driver = Driver::find($request->driver_id);

        $knownPhone = $driver->user?->phone ?? $driver->user?->mobile;
        if ($request->has('phone') && $knownPhone && $knownPhone !== $request->phone) {
            return response()->json([
                'success' => false,
                'message' => 'Phone number does not match driver record',
            ], 403);
        }

        // Update driver location
        $driver->updateLocation(
            $request->latitude,
            $request->longitude,
            $request->speed,
            $request->accuracy
        );

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'data' => [
                'driver_id' => $driver->id,
                'driver_name' => $driver->user?->name ?? $driver->user?->user_full_name ?? $driver->user?->email ?? ('Driver #'.$driver->id),
                'latitude' => (float) $request->latitude,
                'longitude' => (float) $request->longitude,
                'last_update' => optional($driver->last_location_update)->toDateTimeString(),
            ],
        ]);
    }

    /**
     * Update driver status from mobile app
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|exists:drivers,id',
            'status' => 'required|in:available,busy,offline,on_break',
            'phone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $driver = Driver::find($request->driver_id);

        $knownPhone = $driver->user?->phone ?? $driver->user?->mobile;
        if ($request->has('phone') && $knownPhone && $knownPhone !== $request->phone) {
            return response()->json([
                'success' => false,
                'message' => 'Phone number does not match driver record',
            ], 403);
        }

        if (\Illuminate\Support\Facades\Schema::hasColumn('drivers', 'availability')) {
            $driver->update(['availability' => $request->status]);
        } else {
            $driver->update(['status' => $request->status]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => [
                'driver_id' => $driver->id,
                'driver_name' => $driver->user?->name ?? $driver->user?->user_full_name ?? $driver->user?->email ?? ('Driver #'.$driver->id),
                'status' => $driver->availability ?? $driver->status,
            ],
        ]);
    }

    /**
     * Get driver information
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDriverInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|exists:drivers,id',
            'phone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $driver = Driver::with('activeAssignments.order')->find($request->driver_id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $driver->id,
                'name' => $driver->name,
                'phone' => $driver->phone,
                'status' => $driver->status,
                'vehicle_type' => $driver->vehicle_type,
                'vehicle_plate' => $driver->vehicle_plate,
                'total_deliveries' => $driver->total_deliveries,
                'rating' => $driver->rating,
                'active_assignments' => $driver->activeAssignments->map(function ($assignment) {
                    return [
                        'id' => $assignment->id,
                        'order_id' => $assignment->order_id,
                        'status' => $assignment->status,
                        'customer_name' => $assignment->order->customer_name ?? 'N/A',
                        'delivery_address' => $assignment->order->delivery_address ?? 'N/A',
                        'assigned_at' => $assignment->assigned_at->toDateTimeString(),
                    ];
                }),
            ],
        ]);
    }

    /**
     * Batch update location (for multiple updates at once)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function batchUpdateLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|exists:drivers,id',
            'locations' => 'required|array',
            'locations.*.latitude' => 'required|numeric|between:-90,90',
            'locations.*.longitude' => 'required|numeric|between:-180,180',
            'locations.*.speed' => 'nullable|numeric|min:0',
            'locations.*.accuracy' => 'nullable|numeric|min:0',
            'locations.*.timestamp' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $driver = Driver::find($request->driver_id);
        $locationsAdded = 0;

        foreach ($request->locations as $location) {
            $driver->locations()->create([
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude'],
                'speed' => $location['speed'] ?? null,
                'accuracy' => $location['accuracy'] ?? null,
                'recorded_at' => $location['timestamp'] ?? now(),
            ]);
            $locationsAdded++;
        }

        // Update current location to the latest one
        $latestLocation = end($request->locations);
        $driver->update([
            'current_latitude' => $latestLocation['latitude'],
            'current_longitude' => $latestLocation['longitude'],
            'last_location_update' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Successfully added {$locationsAdded} location points",
            'data' => [
                'driver_id' => $driver->id,
                'locations_added' => $locationsAdded,
            ],
        ]);
    }
}
