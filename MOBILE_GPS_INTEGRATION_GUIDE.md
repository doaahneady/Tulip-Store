# 📱 Mobile GPS Integration Guide

## 🎯 Overview

This guide explains how to connect driver phones to send GPS location data to the Driver Supervisor Dashboard in real-time.

---

## ✅ What's New

### 1. Live Updates (Every 5 Seconds)
- Dashboard now updates every **5 seconds** instead of 30 seconds
- Smooth marker animations when drivers move
- No page refresh needed - truly live tracking

### 2. Map Centered on Sweida, Syria
- Map now shows **Sweida, Syria** (32.7081, 36.5686)
- All sample drivers relocated to Sweida area
- Zoom level optimized for city view

### 3. Mobile API Endpoints
Four new API endpoints for driver phones to send GPS data:
- Update single location
- Batch update multiple locations
- Update driver status
- Get driver information

---

## 📡 API Endpoints for Mobile Apps

### Base URL
```
http://your-domain.com/api/driver
```

### 1. Update Location (Single Point)
**Endpoint:** `POST /api/driver/location/update`

**Use Case:** Send GPS location from driver's phone every 5-10 seconds

**Request Body:**
```json
{
  "driver_id": 1,
  "latitude": 32.7081,
  "longitude": 36.5686,
  "speed": 45.5,
  "accuracy": 10.2,
  "phone": "0501234567"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Location updated successfully",
  "data": {
    "driver_id": 1,
    "driver_name": "أحمد محمد",
    "latitude": 32.7081,
    "longitude": 36.5686,
    "last_update": "2024-12-03 10:30:45"
  }
}
```

**cURL Example:**
```bash
curl -X POST http://localhost:8000/api/driver/location/update \
  -H "Content-Type: application/json" \
  -d '{
    "driver_id": 1,
    "latitude": 32.7081,
    "longitude": 36.5686,
    "speed": 45.5,
    "accuracy": 10.2
  }'
```

---

### 2. Batch Update Locations
**Endpoint:** `POST /api/driver/location/batch`

**Use Case:** Send multiple GPS points at once (useful when phone was offline)

**Request Body:**
```json
{
  "driver_id": 1,
  "locations": [
    {
      "latitude": 32.7081,
      "longitude": 36.5686,
      "speed": 45.5,
      "accuracy": 10.2,
      "timestamp": "2024-12-03 10:30:00"
    },
    {
      "latitude": 32.7085,
      "longitude": 36.5690,
      "speed": 50.0,
      "accuracy": 8.5,
      "timestamp": "2024-12-03 10:30:10"
    }
  ]
}
```

**Response:**
```json
{
  "success": true,
  "message": "Successfully added 2 location points",
  "data": {
    "driver_id": 1,
    "locations_added": 2
  }
}
```

---

### 3. Update Driver Status
**Endpoint:** `POST /api/driver/status/update`

**Use Case:** Driver changes status (available, busy, on break, offline)

**Request Body:**
```json
{
  "driver_id": 1,
  "status": "available",
  "phone": "0501234567"
}
```

**Status Options:**
- `available` - Ready for deliveries
- `busy` - Currently delivering
- `on_break` - Taking a break
- `offline` - Not working

**Response:**
```json
{
  "success": true,
  "message": "Status updated successfully",
  "data": {
    "driver_id": 1,
    "driver_name": "أحمد محمد",
    "status": "available"
  }
}
```

---

### 4. Get Driver Info
**Endpoint:** `POST /api/driver/info`

**Use Case:** Get driver details and active assignments

**Request Body:**
```json
{
  "driver_id": 1,
  "phone": "0501234567"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "أحمد محمد",
    "phone": "0501234567",
    "status": "available",
    "vehicle_type": "سيارة صغيرة",
    "vehicle_plate": "أ ب ج 1234",
    "total_deliveries": 145,
    "rating": 4.8,
    "active_assignments": [
      {
        "id": 1,
        "order_id": 123,
        "status": "assigned",
        "customer_name": "محمد أحمد",
        "delivery_address": "السويداء، شارع الثورة",
        "assigned_at": "2024-12-03 10:00:00"
      }
    ]
  }
}
```

---

## 📱 Mobile App Implementation

### Android (Java/Kotlin)

#### 1. Get GPS Permission
```xml
<!-- AndroidManifest.xml -->
<uses-permission android:name="android.permission.ACCESS_FINE_LOCATION" />
<uses-permission android:name="android.permission.ACCESS_COARSE_LOCATION" />
<uses-permission android:name="android.permission.INTERNET" />
```

#### 2. Send GPS Location (Kotlin)
```kotlin
import android.location.Location
import okhttp3.*
import org.json.JSONObject

class LocationService {
    private val client = OkHttpClient()
    private val baseUrl = "http://your-domain.com/api/driver"
    
    fun sendLocation(driverId: Int, location: Location) {
        val json = JSONObject().apply {
            put("driver_id", driverId)
            put("latitude", location.latitude)
            put("longitude", location.longitude)
            put("speed", location.speed)
            put("accuracy", location.accuracy)
        }
        
        val body = RequestBody.create(
            MediaType.parse("application/json"),
            json.toString()
        )
        
        val request = Request.Builder()
            .url("$baseUrl/location/update")
            .post(body)
            .build()
        
        client.newCall(request).enqueue(object : Callback {
            override fun onResponse(call: Call, response: Response) {
                println("Location sent successfully")
            }
            
            override fun onFailure(call: Call, e: IOException) {
                println("Failed to send location: ${e.message}")
            }
        })
    }
}
```

#### 3. Background Location Updates
```kotlin
import android.location.LocationListener
import android.location.LocationManager

class BackgroundLocationService : Service() {
    private lateinit var locationManager: LocationManager
    private val driverId = 1 // Get from login
    
    private val locationListener = LocationListener { location ->
        // Send location every 5 seconds
        sendLocation(driverId, location)
    }
    
    override fun onStartCommand(intent: Intent?, flags: Int, startId: Int): Int {
        locationManager = getSystemService(Context.LOCATION_SERVICE) as LocationManager
        
        // Request location updates every 5 seconds, minimum 10 meters
        locationManager.requestLocationUpdates(
            LocationManager.GPS_PROVIDER,
            5000, // 5 seconds
            10f,  // 10 meters
            locationListener
        )
        
        return START_STICKY
    }
}
```

---

### iOS (Swift)

#### 1. Get GPS Permission
```swift
// Info.plist
<key>NSLocationAlwaysAndWhenInUseUsageDescription</key>
<string>We need your location to track deliveries</string>
<key>NSLocationWhenInUseUsageDescription</key>
<string>We need your location to track deliveries</string>
```

#### 2. Send GPS Location (Swift)
```swift
import CoreLocation
import Foundation

class LocationService: NSObject, CLLocationManagerDelegate {
    let locationManager = CLLocationManager()
    let driverId = 1 // Get from login
    let baseUrl = "http://your-domain.com/api/driver"
    
    override init() {
        super.init()
        locationManager.delegate = self
        locationManager.requestAlwaysAuthorization()
        locationManager.startUpdatingLocation()
        locationManager.distanceFilter = 10 // 10 meters
        locationManager.desiredAccuracy = kCLLocationAccuracyBest
    }
    
    func locationManager(_ manager: CLLocationManager, didUpdateLocations locations: [CLLocation]) {
        guard let location = locations.last else { return }
        sendLocation(location)
    }
    
    func sendLocation(_ location: CLLocation) {
        let url = URL(string: "\(baseUrl)/location/update")!
        var request = URLRequest(url: url)
        request.httpMethod = "POST"
        request.setValue("application/json", forHTTPHeaderField: "Content-Type")
        
        let body: [String: Any] = [
            "driver_id": driverId,
            "latitude": location.coordinate.latitude,
            "longitude": location.coordinate.longitude,
            "speed": location.speed,
            "accuracy": location.horizontalAccuracy
        ]
        
        request.httpBody = try? JSONSerialization.data(withJSONObject: body)
        
        URLSession.shared.dataTask(with: request) { data, response, error in
            if let error = error {
                print("Error sending location: \(error)")
                return
            }
            print("Location sent successfully")
        }.resume()
    }
}
```

---

### React Native

```javascript
import Geolocation from '@react-native-community/geolocation';
import axios from 'axios';

const BASE_URL = 'http://your-domain.com/api/driver';
const DRIVER_ID = 1; // Get from login

// Start tracking
const startLocationTracking = () => {
  const watchId = Geolocation.watchPosition(
    (position) => {
      sendLocation(position.coords);
    },
    (error) => console.log(error),
    {
      enableHighAccuracy: true,
      distanceFilter: 10, // 10 meters
      interval: 5000, // 5 seconds
      fastestInterval: 5000,
    }
  );
  
  return watchId;
};

// Send location to server
const sendLocation = async (coords) => {
  try {
    const response = await axios.post(`${BASE_URL}/location/update`, {
      driver_id: DRIVER_ID,
      latitude: coords.latitude,
      longitude: coords.longitude,
      speed: coords.speed,
      accuracy: coords.accuracy,
    });
    console.log('Location sent:', response.data);
  } catch (error) {
    console.error('Error sending location:', error);
  }
};

// Stop tracking
const stopLocationTracking = (watchId) => {
  Geolocation.clearWatch(watchId);
};
```

---

### Flutter

```dart
import 'package:geolocator/geolocator.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class LocationService {
  final String baseUrl = 'http://your-domain.com/api/driver';
  final int driverId = 1; // Get from login
  
  Future<void> startTracking() async {
    // Check permissions
    LocationPermission permission = await Geolocator.checkPermission();
    if (permission == LocationPermission.denied) {
      permission = await Geolocator.requestPermission();
    }
    
    // Start listening to location updates
    Geolocator.getPositionStream(
      locationSettings: LocationSettings(
        accuracy: LocationAccuracy.high,
        distanceFilter: 10, // 10 meters
      ),
    ).listen((Position position) {
      sendLocation(position);
    });
  }
  
  Future<void> sendLocation(Position position) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/location/update'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'driver_id': driverId,
          'latitude': position.latitude,
          'longitude': position.longitude,
          'speed': position.speed,
          'accuracy': position.accuracy,
        }),
      );
      
      if (response.statusCode == 200) {
        print('Location sent successfully');
      }
    } catch (e) {
      print('Error sending location: $e');
    }
  }
}
```

---

## 🧪 Testing with cURL

### Test Location Update
```bash
# Update driver 1 location
curl -X POST http://localhost:8000/api/driver/location/update \
  -H "Content-Type: application/json" \
  -d '{
    "driver_id": 1,
    "latitude": 32.7081,
    "longitude": 36.5686,
    "speed": 45.5,
    "accuracy": 10.2
  }'
```

### Test Status Update
```bash
# Change driver status to busy
curl -X POST http://localhost:8000/api/driver/status/update \
  -H "Content-Type: application/json" \
  -d '{
    "driver_id": 1,
    "status": "busy"
  }'
```

### Test Batch Update
```bash
curl -X POST http://localhost:8000/api/driver/location/batch \
  -H "Content-Type: application/json" \
  -d '{
    "driver_id": 1,
    "locations": [
      {"latitude": 32.7081, "longitude": 36.5686, "speed": 45.5},
      {"latitude": 32.7085, "longitude": 36.5690, "speed": 50.0}
    ]
  }'
```

---

## 🔄 How Live Updates Work

### Dashboard Side:
1. Dashboard loads and fetches initial driver locations
2. JavaScript polls the API every **5 seconds**
3. When new data arrives, markers smoothly animate to new positions
4. No page refresh needed - truly live tracking

### Mobile Side:
1. Driver app requests GPS permission
2. App starts background location service
3. GPS updates sent to API every **5-10 seconds**
4. Location stored in database
5. Dashboard picks up changes on next poll (5 seconds)

### Result:
- **Near real-time tracking** with 5-10 second delay
- **Smooth animations** when drivers move
- **Battery efficient** with smart update intervals

---

## 🔋 Battery Optimization Tips

### For Mobile Apps:

1. **Adjust Update Frequency:**
   - When moving: Every 5 seconds
   - When stationary: Every 30 seconds
   - When offline: Stop updates

2. **Use Geofencing:**
   - Only track when in delivery area
   - Pause tracking when at warehouse

3. **Batch Updates:**
   - Collect multiple points
   - Send in batches to reduce network calls

4. **Smart Accuracy:**
   - High accuracy when delivering
   - Lower accuracy when returning to base

---

## 🔐 Security Considerations

### 1. Phone Verification
The API accepts an optional `phone` parameter to verify the driver:

```json
{
  "driver_id": 1,
  "phone": "0501234567",
  "latitude": 32.7081,
  "longitude": 36.5686
}
```

### 2. Add Authentication (Recommended)
For production, add token-based authentication:

```php
// Add middleware to routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/location/update', ...);
});
```

### 3. Rate Limiting
Prevent abuse by limiting requests:

```php
Route::middleware(['throttle:60,1'])->group(function () {
    // Max 60 requests per minute
});
```

---

## 📊 Monitoring & Analytics

### Track These Metrics:
- Location update frequency
- GPS accuracy
- Network latency
- Battery consumption
- Failed updates

### Database Queries:
```sql
-- Check recent location updates
SELECT d.name, d.last_location_update, 
       TIMESTAMPDIFF(SECOND, d.last_location_update, NOW()) as seconds_ago
FROM drivers d
WHERE d.is_active = 1
ORDER BY d.last_location_update DESC;

-- Count location points per driver
SELECT d.name, COUNT(dl.id) as location_count
FROM drivers d
LEFT JOIN driver_locations dl ON d.id = dl.driver_id
GROUP BY d.id;
```

---

## 🐛 Troubleshooting

### Location Not Updating?
1. Check driver app has GPS permission
2. Verify internet connection
3. Check API endpoint is accessible
4. Review Laravel logs: `storage/logs/laravel.log`

### Dashboard Not Showing Updates?
1. Check browser console for errors
2. Verify API returns data: `/delivery/supervisor/locations`
3. Clear browser cache
4. Check 5-second polling is working

### GPS Accuracy Issues?
1. Ensure phone has clear sky view
2. Use high accuracy mode
3. Wait for GPS to stabilize (30-60 seconds)
4. Check `accuracy` value in API response

---

## ✅ Quick Start Checklist

- [ ] API endpoints created and tested
- [ ] Mobile app has GPS permission
- [ ] Location service running in background
- [ ] API calls working (test with cURL)
- [ ] Dashboard showing live updates
- [ ] Markers animating smoothly
- [ ] Map centered on Sweida, Syria

---

## 📞 Support

For issues or questions:
- Check Laravel logs: `tail -f storage/logs/laravel.log`
- Test API with Postman or cURL
- Review mobile app logs
- Check network connectivity

---

**🎉 You're ready for live GPS tracking!**

Drivers can now send their location from phones, and supervisors can track them in real-time on the dashboard with smooth animations and 5-second updates.
