# ESP32 RFID API Documentation

This document describes how to connect your ESP32 RFID system to the Laravel doorlock system.

## Setup

### 1. Configure API Token

Add the following to your `.env` file:

```env
API_TOKEN=your-secret-api-token-here
```

Generate a secure random token (e.g., using `php artisan tinker` and `Str::random(32)`).

### 2. API Endpoints

Base URL: `https://your-domain.com/api` (or `http://your-ip/api` for local testing)

#### Health Check

-   **Endpoint**: `GET /api/health`
-   **Authentication**: Required (X-API-Token header)
-   **Response**:

```json
{
    "status": "ok",
    "message": "API is running",
    "timestamp": "2025-12-05T10:30:00+00:00"
}
```

#### Get WiFi Configuration

-   **Endpoint**: `GET /api/wifi-config`
-   **Authentication**: Required (X-API-Token header)
-   **Response**:

```json
{
    "success": true,
    "ssid": "YourWiFiNetwork",
    "password": "YourWiFiPassword",
    "description": "Main office WiFi"
}
```

**Error Response (404)**:

```json
{
    "success": false,
    "message": "No active WiFi configuration found"
}
```

#### Validate RFID Card

-   **Endpoint**: `POST /api/validate`
-   **Authentication**: Required (X-API-Token header)
-   **Request Body**:

```json
{
    "card_number": "1234567890"
}
```

**Success Response (200)**:

```json
{
    "success": true,
    "message": "Access granted",
    "access_granted": true,
    "member": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "card": {
        "number": "1234567890",
        "type": "keyfob",
        "issued_at": "2025-01-01",
        "expires_at": "2025-12-31"
    },
    "subscription": {
        "plan_name": "Monthly Plan",
        "end_date": "2025-12-31",
        "status": "active"
    }
}
```

**Error Responses**:

1. **Card Not Found (404)**:

```json
{
    "success": false,
    "message": "Card not found",
    "access_granted": false
}
```

2. **Card Not Assigned (403)**:

```json
{
    "success": false,
    "message": "Card not assigned to any member",
    "access_granted": false
}
```

3. **Card Inactive/Lost/Stolen (403)**:

```json
{
    "success": false,
    "message": "Card is inactive",
    "access_granted": false,
    "card_status": "inactive"
}
```

4. **Card Expired (403)**:

```json
{
    "success": false,
    "message": "Card has expired",
    "access_granted": false,
    "expires_at": "2025-01-01"
}
```

5. **No Active Subscription (403)**:

```json
{
    "success": false,
    "message": "Member has no active subscription",
    "access_granted": false,
    "member_name": "John Doe"
}
```

6. **Unauthorized (401)**:

```json
{
    "success": false,
    "message": "Unauthorized. Invalid or missing API token."
}
```

## ESP32 Code Examples

### Basic Example (Hardcoded WiFi)

Here's a basic example for ESP32 using Arduino with hardcoded WiFi:

```cpp
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

const char* ssid = "YOUR_WIFI_SSID";
const char* password = "YOUR_WIFI_PASSWORD";
const char* apiUrl = "https://your-domain.com/api/validate";
const char* apiToken = "your-secret-api-token-here";

void setup() {
  Serial.begin(115200);

  // Connect to WiFi
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("WiFi connected!");
}

void validateRFID(String cardNumber) {
  HTTPClient http;

  http.begin(apiUrl);
  http.addHeader("Content-Type", "application/json");
  http.addHeader("X-API-Token", apiToken);

  // Create JSON payload
  String jsonPayload = "{\"card_number\":\"" + cardNumber + "\"}";

  int httpResponseCode = http.POST(jsonPayload);

  if (httpResponseCode > 0) {
    String response = http.getString();
    Serial.println("Response: " + response);

    // Parse JSON response
    DynamicJsonDocument doc(1024);
    deserializeJson(doc, response);

    bool accessGranted = doc["access_granted"];

    if (accessGranted) {
      Serial.println("Access GRANTED!");
      // Open door lock
      // digitalWrite(DOOR_LOCK_PIN, HIGH);
      // delay(2000);
      // digitalWrite(DOOR_LOCK_PIN, LOW);
    } else {
      Serial.println("Access DENIED: " + String(doc["message"].as<const char*>()));
      // Show error on display or LED
    }
  } else {
    Serial.println("Error: " + String(httpResponseCode));
  }

  http.end();
}

void loop() {
  // Read RFID card number from your RFID reader
  // String cardNumber = readRFID(); // Your RFID reading function
  // validateRFID(cardNumber);
  delay(1000);
}
```

### Advanced Example (Dynamic WiFi Configuration)

For a complete example that retrieves WiFi credentials from the API, see `ESP32_WIFI_CONFIG_CODE.ino` in the project root. This example:

-   Retrieves WiFi SSID and password from `/api/wifi-config` endpoint
-   Automatically connects to the configured WiFi network
-   Validates RFID cards via `/api/validate` endpoint
-   Includes door lock control and status LEDs

**Key Features:**

-   Dynamic WiFi configuration (no need to hardcode WiFi credentials)
-   Automatic WiFi reconnection
-   Complete RFID validation flow
-   Hardware control (door lock, LEDs)

See `ESP32_WIFI_CONFIG_README.md` for detailed setup instructions.

## Authentication

All API requests must include the API token in one of two ways:

1. **Header** (Recommended):

    ```
    X-API-Token: your-secret-api-token-here
    ```

2. **Query Parameter** (Alternative):
    ```
    ?api_token=your-secret-api-token-here
    ```

## Testing with cURL

```bash
# Health check
curl -X GET https://your-domain.com/api/health \
  -H "X-API-Token: your-secret-api-token-here"

# Validate card
curl -X POST https://your-domain.com/api/validate \
  -H "Content-Type: application/json" \
  -H "X-API-Token: your-secret-api-token-here" \
  -d '{"card_number":"1234567890"}'
```

## Notes

-   The API validates:

    1. Card exists in the system
    2. Card is assigned to a member
    3. Card status is "active"
    4. Card is not expired
    5. Member has an active subscription

-   All checks must pass for access to be granted.

-   The keyfob expiration date is automatically synced with the member's subscription end date.
