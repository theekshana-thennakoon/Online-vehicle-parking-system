#include <WiFi.h>
#include <FirebaseESP32.h>
#include <SPI.h>
#include <ESP32Servo.h>
#include <LiquidCrystal_I2C.h>

LiquidCrystal_I2C lcd(0x27, 16, 2); 
//WiFi and Firebase Credentials

#define WIFI_SSID "TLT"
#define WIFI_PASSWORD "1209LT1108"

#define API_KEY "AIzaSyCV2TegSk-r8seMnc4KmBJxDCnreX9yLBw"
#define DATABASE_URL "https://parking-gate-d54d5-default-rtdb.firebaseio.com/"

#define IR_SENSOR_1 2    // GPIO2 for first MD0370
#define IR_SENSOR_2 4
#define IR_SENSOR_3 18
#define IR_SENSOR_4 19
#define IR_SENSOR_5 12
#define IR_SENSOR_6 14
#define IR_SENSOR_7 33
#define IR_SENSOR_8 32
int avalible;
#define SERVO_PIN 5

FirebaseData fbdo;
FirebaseAuth auth;
FirebaseConfig config;

Servo gateServo; // Servo object

// Debug helper functions
int count(int s1, int s2, int s3, int s4){
  int occupied = 0;
  if(s1 == 1) occupied++;
  if(s2 == 1) occupied++;
  if(s3 == 1) occupied++;
  if(s4 == 1) occupied++;
  return occupied;
}
void updateDisplay(int avalible) {
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Avalible slots"); 
  lcd.setCursor(2, 1);
  lcd.print("4/");          // Total slots (example)
  lcd.setCursor(5, 1);
  lcd.print(avalible);
}
void debugSetFloat(const char* path, float val) {
  if (Firebase.setFloat(fbdo, path, val)) {
    Serial.printf("[OK] %s = %.2f\n", path, val);
  } else {
    Serial.printf("[ERR] set %s -> %s\n", path, fbdo.errorReason().c_str());
  }
}

void debugSetInt(const char* path, int val) {
  if (Firebase.setInt(fbdo, path, val)) {
    Serial.printf("[OK] %s = %d\n", path, val);
  } else {
    Serial.printf("[ERR] set %s -> %s\n", path, fbdo.errorReason().c_str());
  }
}

void debugSetString(const char* path, const String &val) {
  if (Firebase.setString(fbdo, path, val)) {
    Serial.printf("[OK] %s = %s\n", path, val.c_str());
  } else {
    Serial.printf("[ERR] set %s -> %s\n", path, fbdo.errorReason().c_str());
  }
}

void setup() {

  lcd.init();
  lcd.clear();
  lcd.backlight();  // Make sure backlight is on
  lcd.setCursor(3, 0);
  lcd.print("..WELCOME..");
  delay(2000);

  // Print a message on both lines of the LCD.
  // lcd.setCursor(2, 0);  //Set cursor to character 2 on line 0
  // lcd.print("Hello world!");

  // lcd.setCursor(2, 1);  //Move cursor to character 2 on line 1
  // lcd.print(avalible);

  Serial.end();  // Close any existing serial connection
  delay(1000);   // Wait a second
  Serial.begin(115200);  // Reopen serial
  
  delay(10);

  Serial.println("\n--- START ---");

  // WiFi connection
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  Serial.print("Connecting to Wi-Fi");
  unsigned long start = millis();
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(300);
    if (millis() - start > 20000) {
      Serial.println("\nFailed to connect to WiFi within 20s - check credentials.");
      break;
    }
  }
  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\nConnected to WiFi");
    Serial.print("IP: "); Serial.println(WiFi.localIP());
  }

  // Firebase setup
  config.api_key = API_KEY;
  // IMPORTANT: use database host only (no https:// and no trailing slash)
  config.database_url = DATABASE_URL;

  // Anonymous sign-in
  if (Firebase.signUp(&config, &auth, "", "")) {
      Serial.println("Firebase signup OK");
  } else {
      Serial.printf("Firebase signup failed: %s\n", config.signer.signupError.message.c_str());
  }

  Firebase.begin(&config, &auth);
  Firebase.reconnectWiFi(true);

  // Short delay then test a write right away so we can see any error
  delay(1200);

  Serial.print("Firebase.ready(): ");
  Serial.println(Firebase.ready() ? "true" : "false");

  // Test write to see if auth/database is OK
  if (Firebase.setInt(fbdo, "/test/wifi_connected", 1)) {
    Serial.println("Test write OK -> /test/wifi_connected = 1");
  } else {
    Serial.print("Test write failed: ");
    Serial.println(fbdo.errorReason());
  }

  // Initialize IR sensor pins as input
  pinMode(IR_SENSOR_1, INPUT);
  pinMode(IR_SENSOR_2, INPUT);
  pinMode(IR_SENSOR_3, INPUT);
  pinMode(IR_SENSOR_4, INPUT);
  pinMode(IR_SENSOR_5, INPUT);
  pinMode(IR_SENSOR_6, INPUT);
  pinMode(IR_SENSOR_7, INPUT);
  pinMode(IR_SENSOR_8, INPUT);

  // Attach servo
  gateServo.attach(SERVO_PIN);
  gateServo.write(0); // Start closed
}

void loop() {
  
  
  // Print firebase readiness status (so we can see progress in serial)
  static unsigned long lastStatus = 0;
  if (millis() - lastStatus > 5000) {
    Serial.print("Firebase.ready(): ");
    Serial.println(Firebase.ready() ? "true" : "false");
    lastStatus = millis();
  }

  // If Firebase is not ready, keep trying but don't return immediately (so serial remains active)
  if (!Firebase.ready()) {
    // If not ready for a long time, print last error (if any)
    if (fbdo.httpCode() != 0) {
      Serial.print("Last Firebase HTTP code: ");
      Serial.println(fbdo.httpCode());
      Serial.print("Last error: ");
      Serial.println(fbdo.errorReason());
    }
    delay(1000);
    return;
  }

  bool sensor1_detected = !digitalRead(IR_SENSOR_1);  // Invert: LOW means detected
  bool sensor2_detected = !digitalRead(IR_SENSOR_2); 
  bool sensor3_detected = !digitalRead(IR_SENSOR_3);
  bool sensor4_detected = !digitalRead(IR_SENSOR_4);
  bool sensor5_detected = !digitalRead(IR_SENSOR_5);
  bool sensor6_detected = !digitalRead(IR_SENSOR_6);
  bool sensor7_detected = !digitalRead(IR_SENSOR_7);
  bool sensor8_detected = !digitalRead(IR_SENSOR_8);

  int status1 = (sensor1_detected && sensor2_detected) ? 1 : 0;
  int status2 = (sensor3_detected && sensor4_detected) ? 1 : 0;
  int status3 = (sensor5_detected && sensor6_detected) ? 1 : 0;
  int status4 = (sensor7_detected && sensor8_detected) ? 1 : 0;

  int occupied = count(status1, status2, status3, status4);
  avalible = 4 - occupied;  // Available = Total - Occupied
  updateDisplay(avalible);
  


  // Print current status
  Serial.printf("Sensor1: %s | Sensor2: %s | Status: %d\n",
                sensor1_detected ? "DETECTED" : "CLEAR",
                sensor2_detected ? "DETECTED" : "CLEAR", 
                status1);
  debugSetFloat("Sensor/S2", status1);

  Serial.printf("Sensor3: %s | Sensor4: %s | Status: %d\n",
                sensor3_detected ? "DETECTED" : "CLEAR",
                sensor4_detected ? "DETECTED" : "CLEAR", 
                status2);
  debugSetFloat("Sensor/S4", status2);

   Serial.printf("Sensor5: %s | Sensor6: %s | Status: %d\n",
                sensor5_detected ? "DETECTED" : "CLEAR",
                sensor6_detected ? "DETECTED" : "CLEAR", 
                status3);
  debugSetFloat("Sensor/S6", status3);

   Serial.printf("Sensor7: %s | Sensor8: %s | Status: %d\n",
                sensor7_detected ? "DETECTED" : "CLEAR",
                sensor8_detected ? "DETECTED" : "CLEAR", 
                status4);
  debugSetFloat("Sensor/S8", status4);



  // Read gate control value, gate controll 
  if (Firebase.getInt(fbdo, "gate_status/current/status")) {
    int gateVal = fbdo.intData();
    Serial.printf("Gate value from Firebase: %d\n", gateVal);

    if (gateVal == 1) {
      gateServo.write(90); // Open gate
      delay(5000);
      gateServo.write(0);  // Close gate
      debugSetFloat("gate_status/current/status", 0);
    }
  } else {
    Serial.print("Failed to read Gate value: ");
    Serial.println(fbdo.errorReason());
  }
  

  delay(500);

}