# Laravel 12 API Documentation

This document describes the versioned API endpoints available in the Laravel 12 application following best practices.

## Architecture Overview

- **API Versioning**: Routes are organized in versioned files (`routes/api/v1.php`, `routes/api/v2.php`)
- **Controller Namespacing**: Controllers are namespaced under `App\Http\Controllers\Api\V1` or `App\Http\Controllers\Api\V2`
- **Authentication**: Session-based authentication (no Sanctum required)
- **Response Format**: Consistent JSON responses with status, message, and data fields

## Base URLs

- **API V1**: `/api/v1/`
- **API V2**: `/api/v2/` (future implementation)

## Authentication

The API uses session-based authentication. You need to authenticate before accessing protected endpoints.

### Authentication Flow

1. **Login**: POST to `/api/v1/auth/login` or `/api/v1/auth/admin/login`
2. **Session**: Laravel maintains session automatically
3. **Access**: Use session cookie for subsequent requests

## API V1 Endpoints

### Health Check
- **GET** `/api/v1/health`
- **Description**: Check if API V1 is running
- **Authentication**: None required
- **Response**: 
```json
{
    "status": "success",
    "message": "API V1 is running",
    "timestamp": "2024-01-01T00:00:00.000000Z",
    "version": "1.0.0"
}
```

### Authentication Endpoints

#### User Login
- **POST** `/api/v1/auth/login`
- **Description**: Login as a regular user
- **Authentication**: None required
- **Body**:
```json
{
    "email": "user@example.com",
    "password": "password"
}
```
- **Response**:
```json
{
    "status": "success",
    "message": "Login successful",
    "data": {
        "user": {...},
        "token": "session-based-auth"
    }
}
```

#### User Registration
- **POST** `/api/v1/auth/register`
- **Description**: Register a new user
- **Authentication**: None required
- **Body**:
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

#### Admin Login
- **POST** `/api/v1/auth/admin/login`
- **Description**: Login as an admin
- **Authentication**: None required
- **Body**:
```json
{
    "email": "admin@example.com",
    "password": "password"
}
```

#### Logout
- **POST** `/api/v1/auth/logout`
- **Description**: Logout current user
- **Authentication**: Required

#### Get Current User
- **GET** `/api/v1/auth/user`
- **Description**: Get current authenticated user
- **Authentication**: Required

#### Get Current Admin
- **GET** `/api/v1/auth/admin`
- **Description**: Get current authenticated admin
- **Authentication**: Admin required

### Public Data Endpoints

#### Get Countries
- **GET** `/api/v1/public/countries`
- **Description**: Get list of countries with translations
- **Authentication**: None required
- **Response**:
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "name": "Country Name",
            "translations": [...]
        }
    ]
}
```

#### Get Cities
- **GET** `/api/v1/public/cities/{country_id}`
- **Description**: Get cities by country ID with translations
- **Authentication**: None required

#### Get Districts
- **GET** `/api/v1/public/districts/{city_id}`
- **Description**: Get districts by city ID with translations
- **Authentication**: None required

### Protected User Endpoints

#### Profile Management
- **GET** `/api/v1/profile` - Get current user profile
- **PUT** `/api/v1/profile/update` - Update user profile
- **PUT** `/api/v1/profile/change-password` - Change password
- **Authentication**: Required

#### User Management
- **GET** `/api/v1/users` - Get paginated list of users
- **GET** `/api/v1/users/{id}` - Get specific user
- **POST** `/api/v1/users` - Create new user
- **PUT** `/api/v1/users/{id}` - Update user
- **DELETE** `/api/v1/users/{id}` - Delete user
- **Authentication**: Required

#### Notifications
- **GET** `/api/v1/notifications` - Get user notifications
- **PUT** `/api/v1/notifications/{id}/mark-read` - Mark notification as read
- **Authentication**: Required

### Admin-Only Endpoints

#### Admin Dashboard
- **GET** `/api/v1/admin/dashboard`
- **Description**: Get admin dashboard statistics
- **Authentication**: Admin required
- **Response**:
```json
{
    "status": "success",
    "data": {
        "users_count": 150,
        "admins_count": 5,
        "roles_count": 3,
        "recent_users": [...]
    }
}
```

#### Admin User Management
- **GET** `/api/v1/admin/users` - Get users with roles (admin view)
- **GET** `/api/v1/admin/users/{id}` - Get specific user with roles
- **Authentication**: Admin required

#### Admin Management
- **GET** `/api/v1/admin/admins` - Get list of admins
- **GET** `/api/v1/admin/admins/{id}` - Get specific admin
- **Authentication**: Admin required

#### Role Management
- **GET** `/api/v1/admin/roles` - Get list of roles with permissions
- **GET** `/api/v1/admin/roles/{id}` - Get specific role with permissions
- **Authentication**: Admin required

#### Settings Management
- **GET** `/api/v1/admin/settings` - Get application settings
- **PUT** `/api/v1/admin/settings/{key}` - Update specific setting
- **Authentication**: Admin required

## Error Responses

All error responses follow this consistent format:
```json
{
    "status": "error",
    "message": "Error description",
    "code": 400
}
```

## HTTP Status Codes
- **200**: Success
- **201**: Created
- **400**: Bad Request
- **401**: Unauthorized
- **403**: Forbidden
- **404**: Not Found
- **422**: Validation Error
- **500**: Internal Server Error

## Usage Examples

### User Authentication Flow
```bash
# 1. User Login
curl -X POST http://your-domain.com/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}' \
  -c cookies.txt

# 2. Get User Profile (using session cookie)
curl -X GET http://your-domain.com/api/v1/profile \
  -b cookies.txt

# 3. Logout
curl -X POST http://your-domain.com/api/v1/auth/logout \
  -b cookies.txt
```

### Admin Operations
```bash
# 1. Admin Login
curl -X POST http://your-domain.com/api/v1/auth/admin/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}' \
  -c admin_cookies.txt

# 2. Get Dashboard Data
curl -X GET http://your-domain.com/api/v1/admin/dashboard \
  -b admin_cookies.txt

# 3. Get Users List
curl -X GET http://your-domain.com/api/v1/admin/users \
  -b admin_cookies.txt
```

### Public Data Access
```bash
# Get Countries (no authentication required)
curl -X GET http://your-domain.com/api/v1/public/countries

# Get Cities by Country ID
curl -X GET http://your-domain.com/api/v1/public/cities/1

# Get Districts by City ID
curl -X GET http://your-domain.com/api/v1/public/districts/1
```

## Laravel 12 Best Practices Implemented

### 1. Versioned Route Files
- Routes are organized in `routes/api/v1.php` and `routes/api/v2.php`
- Easy to maintain and extend for future versions

### 2. Proper Controller Namespacing
- Controllers are namespaced under `App\Http\Controllers\Api\V1`
- Clear separation between API versions

### 3. Consistent Response Format
- All responses follow the same structure with `status`, `message`, and `data`
- Easy to handle on the frontend

### 4. Proper Middleware Usage
- Authentication middleware applied appropriately
- Admin-only routes properly protected

### 5. Error Handling
- Consistent error responses
- Proper HTTP status codes
- Try-catch blocks for database operations

## Future V2 Implementation

When implementing V2, follow this structure:

1. **Create V2 Routes**: `routes/api/v2.php`
2. **Create V2 Controllers**: `App\Http\Controllers\Api\V2\`
3. **Update Bootstrap**: Add V2 route loading in `bootstrap/app.php`
4. **Maintain Backward Compatibility**: Keep V1 endpoints working

Example V2 route loading:
```php
// In bootstrap/app.php
Route::prefix('api')->group(function () {
    Route::group([], base_path('routes/api/v1.php'));
    Route::group([], base_path('routes/api/v2.php'));
});
```

## Testing the API

### Using Laravel Tinker
```php
// Test health endpoint
php artisan tinker
>>> Http::get('http://localhost:8000/api/v1/health')
```

### Using Postman
1. Import the API collection
2. Set base URL to your domain
3. Use session cookies for authentication
4. Test all endpoints systematically

This API follows Laravel 12 best practices and provides a solid foundation for building scalable, maintainable applications.
