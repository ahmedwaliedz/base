# API Auth Module - Examples

## N) Example requests (curl)

### Request Activation Code
```bash
curl -X POST http://localhost:8000/api/v1/auth/request-code \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "phone": "+1234567890"
  }'
```

### Login with Password
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "phone": "+1234567890",
    "password": "password123"
  }'
```

### Login with Activation Code
```bash
curl -X POST http://localhost:8000/api/v1/auth/login-code \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "phone": "+1234567890",
    "code": "123456"
  }'
```

### Get User Profile (requires authentication)
```bash
curl -X GET http://localhost:8000/api/v1/auth/me \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Logout
```bash
curl -X POST http://localhost:8000/api/v1/auth/logout \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## O) Postman collection (inline JSON)

```json
{
  "info": {
    "name": "API Auth Module",
    "description": "Phone-based authentication API endpoints",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Request Activation Code",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          },
          {
            "key": "Accept",
            "value": "application/json"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\n  \"phone\": \"+1234567890\"\n}"
        },
        "url": {
          "raw": "{{base_url}}/api/v1/auth/request-code",
          "host": ["{{base_url}}"],
          "path": ["api", "v1", "auth", "request-code"]
        }
      }
    },
    {
      "name": "Login with Password",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          },
          {
            "key": "Accept",
            "value": "application/json"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\n  \"phone\": \"+1234567890\",\n  \"password\": \"password123\"\n}"
        },
        "url": {
          "raw": "{{base_url}}/api/v1/auth/login",
          "host": ["{{base_url}}"],
          "path": ["api", "v1", "auth", "login"]
        }
      }
    },
    {
      "name": "Login with Activation Code",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          },
          {
            "key": "Accept",
            "value": "application/json"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\n  \"phone\": \"+1234567890\",\n  \"code\": \"123456\"\n}"
        },
        "url": {
          "raw": "{{base_url}}/api/v1/auth/login-code",
          "host": ["{{base_url}}"],
          "path": ["api", "v1", "auth", "login-code"]
        }
      }
    },
    {
      "name": "Get User Profile",
      "request": {
        "method": "GET",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          },
          {
            "key": "Accept",
            "value": "application/json"
          },
          {
            "key": "Authorization",
            "value": "Bearer {{auth_token}}"
          }
        ],
        "url": {
          "raw": "{{base_url}}/api/v1/auth/me",
          "host": ["{{base_url}}"],
          "path": ["api", "v1", "auth", "me"]
        }
      }
    },
    {
      "name": "Logout",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          },
          {
            "key": "Accept",
            "value": "application/json"
          },
          {
            "key": "Authorization",
            "value": "Bearer {{auth_token}}"
          }
        ],
        "url": {
          "raw": "{{base_url}}/api/v1/auth/logout",
          "host": ["{{base_url}}"],
          "path": ["api", "v1", "auth", "logout"]
        }
      }
    }
  ],
  "variable": [
    {
      "key": "base_url",
      "value": "http://localhost:8000"
    },
    {
      "key": "auth_token",
      "value": ""
    }
  ]
}
```

## P) .env example additions

```env
# Sanctum Configuration
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,127.0.0.1:8000,::1

# Auth Codes Configuration
AUTH_CODE_LENGTH=6
AUTH_CODE_TTL_MINUTES=10
AUTH_CODE_MAX_ATTEMPTS=5
AUTH_CODE_RESEND_COOLDOWN=60

# SMS Configuration
SMS_DRIVER=log
# For production, use one of these:
# SMS_DRIVER=twilio
# SMS_DRIVER=vonage

# Twilio Configuration (if using Twilio)
TWILIO_ACCOUNT_SID=your_twilio_account_sid
TWILIO_AUTH_TOKEN=your_twilio_auth_token
TWILIO_FROM_NUMBER=your_twilio_phone_number

# Vonage Configuration (if using Vonage)
VONAGE_API_KEY=your_vonage_api_key
VONAGE_API_SECRET=your_vonage_api_secret
VONAGE_FROM_NUMBER=your_vonage_phone_number
```

## Q) README snippet (setup steps)

### Setup Instructions

1. **Install Sanctum**:
   ```bash
   composer require laravel/sanctum
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   ```

2. **Run Migrations**:
   ```bash
   php artisan migrate
   ```

3. **Configure Environment**:
   Add the environment variables from section P to your `.env` file.

4. **Test the API**:
   Use the curl examples or import the Postman collection to test the endpoints.

### API Endpoints

- `POST /api/v1/auth/request-code` - Request activation code
- `POST /api/v1/auth/login` - Login with phone and password
- `POST /api/v1/auth/login-code` - Login with phone and activation code
- `GET /api/v1/auth/me` - Get authenticated user profile
- `POST /api/v1/auth/logout` - Logout and revoke token

### Response Format

All responses follow this structure:
```json
{
  "status": "success|error",
  "message": "Human readable message",
  "data": {}, // Present on success
  "errors": {}, // Present on validation errors
  "meta": {} // Present when additional metadata is needed
}
```

### Security Features

- Rate limiting on request-code endpoint (3 requests per minute)
- Activation code expiration (10 minutes by default)
- Maximum attempt limits (5 attempts by default)
- Cooldown period between code requests (60 seconds by default)
- Timing-safe password and code verification
- Token-based authentication with Sanctum

### Profile Completeness

The system checks for required profile fields (configurable in `config/auth_codes.php`). By default, only `name` is required. When logging in with activation code, if the profile is incomplete, the response will include:

```json
{
  "status": "success",
  "message": "Logged in; profile incomplete",
  "data": {
    "token": "...",
    "user": {...},
    "profile_next_steps": ["name"]
  },
  "meta": {
    "requires_profile_completion": true
  }
}
```
