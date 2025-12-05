# ESP32 WiFi Configuration Code

This ESP32 code retrieves WiFi credentials from your Laravel dashboard and uses them to connect to your WiFi network.

## Features

-   **Dynamic WiFi Configuration**: Retrieves WiFi SSID and password from your Laravel API
-   **Automatic Reconnection**: Automatically reconnects if WiFi connection is lost
-   **RFID Validation**: Validates RFID cards with your Laravel backend
-   **Door Lock Control**: Controls door lock based on access validation
-   **Status LEDs**: Visual feedback for success/error states

## Setup Instructions

### 1. Install Required Libraries

Open Arduino IDE and install these libraries via Library Manager:

-   **ArduinoJson** by Benoit Blanchon (version 6.x or 7.x)
-   **WiFiManager** by tzapu (optional, for AP mode configuration)

### 2. Configure API Settings

Edit the following variables in the code:

```cpp
const char* apiUrl = "https://your-domain.com/api";  // Your Laravel server URL
const char* apiToken = "your-secret-api-token-here";  // Your API token from .env
```

**To find your API token:**

-   Check your `.env` file for `API_TOKEN`
-   Or generate a new one: `php artisan tinker` → `Str::random(32)`

### 3. Configure Temporary WiFi (Optional)

If you need an initial WiFi connection to fetch credentials:

```cpp
const char* tempSSID = "TEMP_WIFI";      // Temporary WiFi SSID
const char* tempPassword = "TEMP_PASS";   // Temporary WiFi password
```

**Alternative:** Use WiFiManager library to create an Access Point mode for initial configuration.

### 4. Configure Hardware Pins

Adjust these pins based on your hardware setup:

```cpp
#define DOOR_LOCK_PIN 2        // Door lock relay pin
#define LED_SUCCESS_PIN 4       // Success LED pin
#define LED_ERROR_PIN 5         // Error LED pin
#define RFID_RX_PIN 16         // RFID reader RX pin
#define RFID_TX_PIN 17         // RFID reader TX pin
```

### 5. Implement RFID Reading

The `readRFID()` function needs to be implemented based on your RFID reader module:

**For MFRC522:**

```cpp
#include <SPI.h>
#include <MFRC522.h>

#define SS_PIN 5
#define RST_PIN 0

MFRC522 mfrc522(SS_PIN, RST_PIN);

String readRFID() {
  if (!mfrc522.PICC_IsNewCardPresent() || !mfrc522.PICC_ReadCardSerial()) {
    return "";
  }

  String cardNumber = "";
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    if (mfrc522.uid.uidByte[i] < 0x10) cardNumber += "0";
    cardNumber += String(mfrc522.uid.uidByte[i], HEX);
  }
  cardNumber.toUpperCase();
  mfrc522.PICC_HaltA();
  return cardNumber;
}
```

**For PN532 (I2C):**

```cpp
#include <Wire.h>
#include <PN532_I2C.h>
#include <PN532.h>

PN532_I2C pn532_i2c(Wire);
PN532 nfc(pn532_i2c);

String readRFID() {
  uint8_t uid[] = { 0, 0, 0, 0, 0, 0, 0 };
  uint8_t uidLength;

  if (nfc.readPassiveTargetID(PN532_MIFARE_ISO14443A, uid, &uidLength)) {
    String cardNumber = "";
    for (uint8_t i = 0; i < uidLength; i++) {
      if (uid[i] < 0x10) cardNumber += "0";
      cardNumber += String(uid[i], HEX);
    }
    cardNumber.toUpperCase();
    return cardNumber;
  }
  return "";
}
```

## How It Works

1. **Initial Setup**: ESP32 connects to temporary WiFi (or uses WiFiManager AP mode)
2. **Fetch WiFi Config**: Calls `/api/wifi-config` endpoint to get WiFi credentials
3. **Connect to WiFi**: Disconnects from temporary WiFi and connects to configured WiFi
4. **RFID Validation**: Reads RFID cards and validates them via `/api/validate` endpoint
5. **Door Control**: Opens door lock if access is granted

## API Endpoints Used

### GET /api/wifi-config

Retrieves active WiFi configuration.

**Response:**

```json
{
    "success": true,
    "ssid": "YourWiFiName",
    "password": "YourWiFiPassword",
    "description": "Main office WiFi"
}
```

### GET /api/health

Health check endpoint.

**Response:**

```json
{
    "status": "ok",
    "message": "API is running",
    "timestamp": "2025-12-05T10:30:00+00:00"
}
```

### POST /api/validate

Validates RFID card.

**Request:**

```json
{
    "card_number": "1234567890"
}
```

**Response (Success):**

```json
{
    "success": true,
    "message": "Access granted",
    "access_granted": true,
    "member": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    }
}
```

## Troubleshooting

### WiFi Connection Issues

-   Check that WiFi credentials are correctly set in the dashboard
-   Ensure the WiFi configuration is marked as "Active"
-   Verify your WiFi network is accessible

### API Connection Issues

-   Verify API URL is correct and accessible
-   Check API token is correct
-   Ensure server has SSL certificate (for HTTPS) or use HTTP for local testing

### RFID Reading Issues

-   Implement the `readRFID()` function based on your RFID module
-   Check wiring connections
-   Verify RFID module is powered correctly

## Security Notes

-   **API Token**: Keep your API token secure. Don't hardcode it in production code if possible.
-   **HTTPS**: Use HTTPS in production to encrypt WiFi credentials in transit.
-   **WiFi Password**: Consider encrypting WiFi passwords in the database for additional security.

## Advanced: Using WiFiManager

For a better user experience, you can use WiFiManager to create an Access Point mode:

```cpp
#include <WiFiManager.h>

void setup() {
  WiFiManager wifiManager;

  // Create AP for configuration
  wifiManager.autoConnect("ESP32-Doorlock-Setup");

  // After connecting, fetch WiFi config from API
  getWifiConfigFromAPI();
  // ... rest of setup
}
```

This allows users to configure the ESP32 via a web interface without needing to modify code.
