# 🚚 Driver Supervisor Dashboard - Complete Guide

## Overview
A comprehensive real-time GPS tracking and driver management system for delivery supervisors. Track drivers on an interactive map, monitor their status, and manage delivery assignments efficiently.

## Features

### 📍 Real-Time GPS Tracking
- Live location updates every 30 seconds
- Interactive map with custom markers
- Driver location history tracking
- Speed and accuracy monitoring

### 👥 Driver Management
- Complete driver profiles with contact info
- Vehicle details (type, plate number)
- Driver status tracking (Available, Busy, On Break, Offline)
- Performance metrics (total deliveries, ratings)

### 📊 Dashboard Statistics
- Total active drivers
- Available drivers count
- Busy drivers count
- Active deliveries
- Completed deliveries today

### 🗺️ Interactive Map Features
- Filter drivers by status (All, Available, Busy)
- Click on markers to see driver details
- Auto-zoom to fit all drivers
- Custom color-coded markers by status

### 📱 Driver Panel
- Scrollable list of all drivers
- Real-time status updates
- Quick access to driver information
- Active delivery assignments display

## Installation

### 1. Run Migrations
```bash
php artisan migrate
```

This will create the following tables:
- `drivers` - Driver information and current location
- `driver_locations` - Location history tracking
- `delivery_assignments` - Delivery task assignments

### 2. Seed Sample Data
```bash
php artisan db:seed --class=DriverSeeder
```

This creates 6 sample drivers with:
- Different vehicle types
- Various statuses
- Location history
- Performance metrics

### 3. Access the Dashboard
Navigate to: `http://your-domain/delivery/supervisor/dashboard`

## Database Schema

### Drivers Table
```
- id
- name
- phone (unique)
- email (unique, nullable)
- license_number (unique)
- vehicle_type
- vehicle_plate
- status (available, busy, offline, on_break)
- current_latitude
- current_longitude
- last_location_update
- total_deliveries
- rating (0-5)
- is_active
- timestamps
```

### Driver Locations Table
```
- id
- driver_id (foreign key)
- latitude
- longitude
- speed (km/h)
- accuracy (meters)
- address (nullable)
- recorded_at
- timestamps
```

### Delivery Assignments Table
```
- id
- driver_id (foreign key)
- order_id (foreign key)
- status (assigned, picked_up, in_transit, delivered, failed, cancelled)
- assigned_at
- picked_up_at
- delivered_at
- delivery_latitude
- delivery_longitude
- notes
- customer_signature
- timestamps
```

## API Endpoints

### Get Driver Locations
```
GET /delivery/supervisor/locations
```
Returns all active drivers with their current locations and assignments.

### Update Driver Location
```
POST /delivery/supervisor/drivers/{driver}/location
Body: {
    "latitude": 24.7136,
    "longitude": 46.6753,
    "speed": 45.5,
    "accuracy": 10.2
}
```

### Assign Driver to Order
```
POST /delivery/supervisor/assign-driver
Body: {
    "driver_id": 1,
    "order_id": 123
}
```

### Update Delivery Status
```
POST /delivery/supervisor/assignments/{assignment}/status
Body: {
    "status": "delivered",
    "notes": "Delivered successfully",
    "latitude": 24.7136,
    "longitude": 46.6753
}
```

### Get Driver History
```
GET /delivery/supervisor/drivers/{driver}/history
```
Returns paginated delivery history for a specific driver.

## Usage Guide

### For Supervisors

#### Monitoring Drivers
1. Open the dashboard
2. View all drivers on the map
3. Use filters to show specific driver statuses
4. Click on markers or driver cards for details

#### Assigning Deliveries
1. Check available drivers
2. Use the assign driver API endpoint
3. Monitor delivery progress in real-time

#### Tracking Performance
- View driver statistics in the side panel
- Check total deliveries and ratings
- Monitor active assignments

### For Developers

#### Adding New Drivers
```php
Driver::create([
    'name' => 'Driver Name',
    'phone' => '0501234567',
    'email' => 'driver@example.com',
    'license_number' => 'LIC-XXX',
    'vehicle_type' => 'Car',
    'vehicle_plate' => 'ABC 1234',
    'status' => 'available',
    'is_active' => true,
]);
```

#### Updating Driver Location
```php
$driver->updateLocation(
    latitude: 24.7136,
    longitude: 46.6753,
    speed: 45.5,
    accuracy: 10.2
);
```

#### Creating Delivery Assignment
```php
DeliveryAssignment::create([
    'driver_id' => $driver->id,
    'order_id' => $order->id,
    'status' => 'assigned',
    'assigned_at' => now(),
]);
```

## Status Colors

- **Available** (Green): Driver is ready for new assignments
- **Busy** (Blue): Driver has active deliveries
- **On Break** (Yellow): Driver is taking a break
- **Offline** (Gray): Driver is not currently working

## Auto-Refresh

The dashboard automatically refreshes driver locations every 30 seconds to ensure real-time accuracy.

## Mobile Integration

To integrate with a mobile driver app:

1. **Location Updates**: Mobile app should POST to `/delivery/supervisor/drivers/{driver}/location` every 30-60 seconds
2. **Status Updates**: Driver can change status through the app
3. **Assignment Notifications**: Use push notifications when new deliveries are assigned

## Security Considerations

- Ensure proper authentication middleware is applied
- Validate GPS coordinates before storing
- Implement rate limiting on location update endpoints
- Use HTTPS for all API communications
- Sanitize user inputs

## Performance Optimization

- Location history is indexed by driver_id and recorded_at
- Consider archiving old location data after 30 days
- Use database query optimization for large datasets
- Implement caching for frequently accessed data

## Future Enhancements

- [ ] Route optimization suggestions
- [ ] Estimated delivery time calculations
- [ ] Driver chat/messaging system
- [ ] Push notifications for supervisors
- [ ] Advanced analytics and reports
- [ ] Geofencing alerts
- [ ] Traffic integration
- [ ] Delivery proof of delivery photos
- [ ] Customer signature capture
- [ ] Multi-language support

## Troubleshooting

### Map Not Loading
- Check internet connection
- Verify Leaflet.js is loaded correctly
- Check browser console for errors

### Drivers Not Appearing
- Ensure drivers have valid latitude/longitude
- Check that drivers are marked as active
- Verify database connection

### Location Not Updating
- Check auto-refresh is working (30-second interval)
- Verify API endpoint is accessible
- Check for JavaScript errors in console

## Support

For issues or questions:
- Check the Laravel logs: `storage/logs/laravel.log`
- Review browser console for JavaScript errors
- Verify database migrations are complete

## Credits

Built with:
- Laravel (Backend)
- Leaflet.js (Interactive Maps)
- OpenStreetMap (Map Tiles)
- Cairo Font (Arabic Typography)

---

**Version**: 1.0.0  
**Last Updated**: December 3, 2024  
**Author**: Tulip Store Development Team
