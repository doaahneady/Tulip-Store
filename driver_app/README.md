# Tulip Driver App

Flutter mobile application for Tulip delivery drivers to manage and track their assigned orders.

## Features

- **Driver Login**: Secure authentication using username and password
- **Order Management**: View all assigned orders with different statuses
- **Order Details**: Complete information about each order including customer details and items
- **Status Updates**: Mark orders as picked up or delivered
- **Statistics**: View delivery statistics (today, this week, total)
- **Direct Communication**: Call customers directly from the app
- **Navigation**: Open Google Maps for delivery addresses
- **Real-time Updates**: Pull to refresh order list

## Order Statuses

1. **Assigned (معينة)**: Order has been assigned to the driver
2. **Picked Up (جاري التوصيل)**: Driver has picked up the order from the store
3. **Delivered (تم التوصيل)**: Order has been successfully delivered to the customer

## Installation

### Prerequisites

- Flutter SDK (3.0.0 or higher)
- Android Studio / Xcode
- A device or emulator

### Setup

1. Clone the repository
2. Navigate to the driver_app directory
3. Install dependencies:
   ```bash
   flutter pub get
   ```

4. Update the API base URL in `lib/services/api_service.dart` and `lib/services/auth_service.dart`:
   ```dart
   static const String baseUrl = 'https://your-server-url.com';
   ```

5. Run the app:
   ```bash
   flutter run
   ```

## API Endpoints Required

The app expects the following API endpoints on the backend:

### Authentication
- `POST /api/driver/login` - Driver login
- `GET /api/driver/verify` - Verify authentication token

### Orders
- `GET /api/driver/orders?status={status}` - Get driver's orders
- `GET /api/driver/orders/{id}` - Get order details
- `POST /api/driver/orders/{id}/status` - Update order status

### Statistics
- `GET /api/driver/statistics` - Get driver statistics

### Location
- `POST /api/driver/location` - Update driver location

## Configuration

### Android Permissions

Add to `android/app/src/main/AndroidManifest.xml`:
```xml
<uses-permission android:name="android.permission.INTERNET"/>
<uses-permission android:name="android.permission.ACCESS_FINE_LOCATION"/>
<uses-permission android:name="android.permission.ACCESS_COARSE_LOCATION"/>
<uses-permission android:name="android.permission.CALL_PHONE"/>
```

### iOS Permissions

Add to `ios/Runner/Info.plist`:
```xml
<key>NSLocationWhenInUseUsageDescription</key>
<string>We need your location to track deliveries</string>
<key>NSPhoneCallUsageDescription</key>
<string>We need permission to call customers</string>
```

## Building for Production

### Android
```bash
flutter build apk --release
```

### iOS
```bash
flutter build ios --release
```

## Project Structure

```
lib/
├── main.dart                 # App entry point
├── screens/
│   ├── login_screen.dart     # Login page
│   ├── home_screen.dart      # Main dashboard with order list
│   └── order_details_screen.dart  # Order details and actions
└── services/
    ├── auth_service.dart     # Authentication logic
    └── api_service.dart      # API communication
```

## Screenshots

### Login Screen
- Clean and simple login interface
- Username and password fields
- Secure authentication

### Home Screen
- Tabbed interface for different order statuses
- Statistics cards showing delivery metrics
- Order list with customer information
- Pull to refresh functionality

### Order Details
- Complete order information
- Customer contact details
- Order items list
- Action buttons for status updates
- Direct call and navigation buttons

## Support

For issues or questions, contact the development team.

## License

Proprietary - Tulip Store
