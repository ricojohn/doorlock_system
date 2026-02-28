/*
 * ESP32 RFID Doorlock System with Dynamic WiFi Configuration
 * 
 * This code retrieves WiFi credentials from the Laravel API
 * and uses them to connect to WiFi, then validates RFID cards.
 * 
 * Requirements:
 * - ArduinoJson library (install via Library Manager)
 * - ESP32 board support
 */

#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

// API Configuration
const char* apiUrl = "https://your-domain.com/api";  // Change to your server URL
const char* apiToken = "your-secret-api-token-here";  // Change to your API token

// Temporary WiFi for initial connection (if needed)
// You can use WiFiManager library for AP mode instead
const char* tempSSID = "TEMP_WIFI";      // Optional: temporary WiFi for initial setup
const char* tempPassword = "TEMP_PASS";   // Optional: temporary WiFi password

// WiFi credentials from API
String wifiSSID = "";
String wifiPassword = "";

// RFID Reader pins (adjust based on your hardware)
#define RFID_RX_PIN 16
#define RFID_TX_PIN 17

// Door lock control pin
#define DOOR_LOCK_PIN 2

// Status LED pins (optional)
#define LED_SUCCESS_PIN 4
#define LED_ERROR_PIN 5

// Poll admin "open door" command every 1.5 seconds
#define DOOR_COMMAND_POLL_MS 1500
unsigned long lastDoorCommandPoll = 0;

void setup() {
  Serial.begin(115200);
  delay(1000);

  Serial.println("\n=== ESP32 RFID Doorlock System ===");
  Serial.println("Initializing...");

  // Initialize pins
  pinMode(DOOR_LOCK_PIN, OUTPUT);
  digitalWrite(DOOR_LOCK_PIN, LOW);
  
  pinMode(LED_SUCCESS_PIN, OUTPUT);
  pinMode(LED_ERROR_PIN, OUTPUT);
  digitalWrite(LED_SUCCESS_PIN, LOW);
  digitalWrite(LED_ERROR_PIN, LOW);

  // Step 1: Connect to temporary WiFi (or use WiFiManager)
  if (strlen(tempSSID) > 0 && strlen(tempPassword) > 0) {
    Serial.println("Connecting to temporary WiFi...");
    WiFi.begin(tempSSID, tempPassword);
    
    int attempts = 0;
    while (WiFi.status() != WL_CONNECTED && attempts < 20) {
      delay(500);
      Serial.print(".");
      attempts++;
    }
    
    if (WiFi.status() != WL_CONNECTED) {
      Serial.println("\nFailed to connect to temporary WiFi!");
      Serial.println("Please check your temporary WiFi credentials or use WiFiManager.");
      // You can use WiFiManager here to create an AP for configuration
      return;
    }
    
    Serial.println("\nConnected to temporary WiFi!");
    Serial.print("IP Address: ");
    Serial.println(WiFi.localIP());
  } else {
    // Alternative: Use WiFiManager to create AP mode for configuration
    // WiFiManager wifiManager;
    // wifiManager.autoConnect("ESP32-Doorlock-Setup");
    Serial.println("No temporary WiFi configured. Using WiFiManager or hardcoded credentials.");
    // For now, we'll try to get WiFi config from API using existing connection
    // If no connection exists, you need to implement WiFiManager
  }

  // Step 2: Get WiFi configuration from API
  Serial.println("\nFetching WiFi configuration from API...");
  if (getWifiConfigFromAPI()) {
    Serial.println("WiFi configuration retrieved successfully!");
    Serial.print("SSID: ");
    Serial.println(wifiSSID);
    Serial.print("Password: ");
    Serial.println("***"); // Don't print password in production
    
    // Step 3: Disconnect from temporary WiFi and connect to configured WiFi
    if (WiFi.status() == WL_CONNECTED) {
      WiFi.disconnect();
      delay(1000);
    }
    
    Serial.println("\nConnecting to configured WiFi...");
    WiFi.begin(wifiSSID.c_str(), wifiPassword.c_str());
    
    int attempts = 0;
    while (WiFi.status() != WL_CONNECTED && attempts < 30) {
      delay(500);
      Serial.print(".");
      attempts++;
    }
    
    if (WiFi.status() == WL_CONNECTED) {
      Serial.println("\nConnected to configured WiFi!");
      Serial.print("IP Address: ");
      Serial.println(WiFi.localIP());
      
      // Test API connection
      testAPIConnection();
    } else {
      Serial.println("\nFailed to connect to configured WiFi!");
      Serial.println("Please check the WiFi credentials in the dashboard.");
      digitalWrite(LED_ERROR_PIN, HIGH);
    }
  } else {
    Serial.println("Failed to retrieve WiFi configuration from API!");
    Serial.println("Please check your API URL and token.");
    digitalWrite(LED_ERROR_PIN, HIGH);
  }

  Serial.println("\nSystem ready! Waiting for RFID cards...");
}

void loop() {
  // Check WiFi connection
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("WiFi disconnected. Attempting to reconnect...");
    WiFi.begin(wifiSSID.c_str(), wifiPassword.c_str());
    
    int attempts = 0;
    while (WiFi.status() != WL_CONNECTED && attempts < 20) {
      delay(500);
      attempts++;
    }
    
    if (WiFi.status() == WL_CONNECTED) {
      Serial.println("WiFi reconnected!");
    } else {
      Serial.println("WiFi reconnection failed!");
      delay(5000);
      return;
    }
  }

  // Check for remote "open door" command from admin panel (main door only)
  if (WiFi.status() == WL_CONNECTED && (millis() - lastDoorCommandPoll >= DOOR_COMMAND_POLL_MS)) {
    lastDoorCommandPoll = millis();
    checkRemoteDoorCommand();
  }

  // Read RFID card (implement your RFID reading logic here)
  String cardNumber = readRFID();
  
  if (cardNumber.length() > 0) {
    Serial.print("Card detected: ");
    Serial.println(cardNumber);
    
    // Validate card with API
    validateRFID(cardNumber);
  }
  
  delay(100); // Small delay to prevent overwhelming the system
}

/**
 * Get WiFi configuration from Laravel API
 */
bool getWifiConfigFromAPI() {
  HTTPClient http;
  String url = String(apiUrl) + "/wifi-config";
  
  http.begin(url);
  http.addHeader("Content-Type", "application/json");
  http.addHeader("X-API-Token", apiToken);
  
  int httpResponseCode = http.GET();
  
  if (httpResponseCode > 0) {
    String response = http.getString();
    Serial.print("API Response Code: ");
    Serial.println(httpResponseCode);
    Serial.print("API Response: ");
    Serial.println(response);
    
    // Parse JSON response
    DynamicJsonDocument doc(1024);
    DeserializationError error = deserializeJson(doc, response);
    
    if (error) {
      Serial.print("JSON parsing failed: ");
      Serial.println(error.c_str());
      http.end();
      return false;
    }
    
    if (doc["success"] == true) {
      wifiSSID = doc["ssid"].as<String>();
      wifiPassword = doc["password"].as<String>();
      http.end();
      return true;
    } else {
      Serial.print("API Error: ");
      Serial.println(doc["message"].as<const char*>());
      http.end();
      return false;
    }
  } else {
    Serial.print("HTTP Error: ");
    Serial.println(httpResponseCode);
    http.end();
    return false;
  }
}

/**
 * Test API connection
 */
void testAPIConnection() {
  HTTPClient http;
  String url = String(apiUrl) + "/health";
  
  http.begin(url);
  http.addHeader("X-API-Token", apiToken);
  
  int httpResponseCode = http.GET();
  
  if (httpResponseCode == 200) {
    String response = http.getString();
    Serial.println("API Health Check: OK");
    digitalWrite(LED_SUCCESS_PIN, HIGH);
  } else {
    Serial.print("API Health Check Failed: ");
    Serial.println(httpResponseCode);
    digitalWrite(LED_ERROR_PIN, HIGH);
  }
  
  http.end();
}

/**
 * Poll API for remote "open door" command (admin panel - main door only).
 * When open is requested, unlocks for 2 seconds then re-locks.
 */
void checkRemoteDoorCommand() {
  HTTPClient http;
  String url = String(apiUrl) + "/door/command";

  http.begin(url);
  http.addHeader("X-API-Token", apiToken);

  int httpResponseCode = http.GET();

  if (httpResponseCode == 200) {
    String response = http.getString();
    DynamicJsonDocument doc(128);
    DeserializationError error = deserializeJson(doc, response);

    if (!error && doc["open"] == true) {
      Serial.println("=== REMOTE OPEN (Main Door) ===");

      digitalWrite(DOOR_LOCK_PIN, HIGH);
      digitalWrite(LED_SUCCESS_PIN, HIGH);
      digitalWrite(LED_ERROR_PIN, LOW);

      delay(2000);

      digitalWrite(DOOR_LOCK_PIN, LOW);
      digitalWrite(LED_SUCCESS_PIN, LOW);
    }
  }

  http.end();
}

/**
 * Validate RFID card with Laravel API
 */
void validateRFID(String cardNumber) {
  HTTPClient http;
  String url = String(apiUrl) + "/validate";
  
  http.begin(url);
  http.addHeader("Content-Type", "application/json");
  http.addHeader("X-API-Token", apiToken);
  
  // Create JSON payload
  DynamicJsonDocument doc(256);
  doc["card_number"] = cardNumber;
  String jsonPayload;
  serializeJson(doc, jsonPayload);
  
  int httpResponseCode = http.POST(jsonPayload);
  
  if (httpResponseCode > 0) {
    String response = http.getString();
    
    // Parse JSON response
    DynamicJsonDocument responseDoc(2048);
    DeserializationError error = deserializeJson(responseDoc, response);
    
    if (error) {
      Serial.print("JSON parsing failed: ");
      Serial.println(error.c_str());
      http.end();
      return;
    }
    
    bool accessGranted = responseDoc["access_granted"];
    
    if (accessGranted) {
      Serial.println("=== ACCESS GRANTED ===");
      Serial.print("Member: ");
      Serial.println(responseDoc["member"]["name"].as<const char*>());
      
      // Open door lock
      digitalWrite(DOOR_LOCK_PIN, HIGH);
      digitalWrite(LED_SUCCESS_PIN, HIGH);
      digitalWrite(LED_ERROR_PIN, LOW);
      
      delay(2000); // Keep door open for 2 seconds
      
      // Close door lock
      digitalWrite(DOOR_LOCK_PIN, LOW);
      digitalWrite(LED_SUCCESS_PIN, LOW);
    } else {
      Serial.println("=== ACCESS DENIED ===");
      Serial.print("Reason: ");
      Serial.println(responseDoc["message"].as<const char*>());
      
      // Show error indication
      digitalWrite(LED_ERROR_PIN, HIGH);
      digitalWrite(LED_SUCCESS_PIN, LOW);
      
      delay(1000);
      digitalWrite(LED_ERROR_PIN, LOW);
    }
  } else {
    Serial.print("HTTP Error: ");
    Serial.println(httpResponseCode);
    digitalWrite(LED_ERROR_PIN, HIGH);
  }
  
  http.end();
}

/**
 * Read RFID card number
 * Implement this function based on your RFID reader module
 * Common modules: MFRC522, PN532, etc.
 */
String readRFID() {
  // TODO: Implement your RFID reading logic here
  // Example for MFRC522:
  /*
  if (!mfrc522.PICC_IsNewCardPresent() || !mfrc522.PICC_ReadCardSerial()) {
    return "";
  }
  
  String cardNumber = "";
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    if (mfrc522.uid.uidByte[i] < 0x10) cardNumber += "0";
    cardNumber += String(mfrc522.uid.uidByte[i], HEX);
  }
  cardNumber.toUpperCase();
  return cardNumber;
  */
  
  // For testing, you can return a test card number
  // Remove this in production
  if (Serial.available() > 0) {
    String input = Serial.readStringUntil('\n');
    input.trim();
    if (input.length() > 0) {
      return input;
    }
  }
  
  return "";
}
