//Library
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <UniversalTelegramBot.h>
#include <OneWire.h>
#include <DallasTemperature.h>
#include <TimeLib.h>

//pin for ultrasonic sensor
#define echoPin 17
#define trigPin 18

//URL for web-based monitoring
String URL = "http://192.168.193.212/aquaponicmonitoringsystem/aquaponicmonitoringsystem.php";

//Wi-Fi setting
const char* ssid = "realme 7i";
const char* password = "12345678";

// Telegram bot settings
const char* BOT_TOKEN = "6558891117:AAGOVWNOd3zHTG1yAIQv9bzvt73woc5-ifU";
const char* CHAT_ID = "392168979";

// GPIO where the DS18B20 is connected to
const int oneWireBus = 4;

// Setup a oneWire instance to communicate with any OneWire devices
OneWire oneWire(oneWireBus);

// Pass oneWire reference to Dallas Temperature sensor
DallasTemperature sensors(&oneWire);

// Function prototype
void connectWiFi();

// for ph sensor
float calibration_value = 21.34 - 0.2;
int phval = 0;
unsigned long int avgval;
int buffer_arr[10], temp;

// for ultrasonic sensor
long duration, distance;

WiFiClientSecure client;
UniversalTelegramBot bot(BOT_TOKEN, client);

bool motionDetected = false;

void setup() {
  // Start the Serial Monitor
  Serial.begin(9600);

  // Start the DS18B20 sensor
  sensors.begin();

  // Start the ultrasonic sensor
  pinMode(trigPin, OUTPUT);
  pinMode(echoPin, INPUT);

  // Initialize Time library
  configTime(0, 0, "pool.ntp.org");  // You can use a different NTP server if you prefer
  while (!time(nullptr)) {
    delay(1000);
  }

  connectWiFi();
}

void loop() 
{
  if (WiFi.status() != WL_CONNECTED) {
    connectWiFi();
  }

  // Temperature
  sensors.requestTemperatures();
  float tempC = sensors.getTempCByIndex(0);
  Serial.println("Temperature:" );
  Serial.print(tempC);
  Serial.println("ºC");

  // Notification for temp
    if (tempC < 18){
      String message = "ALERT Water temperature too low: " + String(tempC) + " ºC";
      message += " - Time: " + getTimeStamp();
      bot.sendMessage(CHAT_ID, message);
    }else if (tempC > 30){
      String message = "ALERT Water temperature too high: " + String(tempC) + " ºC";
      message += " - Time: " + getTimeStamp();
      bot.sendMessage(CHAT_ID, message);
    }
    
  // pH
  for (int i = 0; i < 10; i++) {
    buffer_arr[i] = analogRead(33);
  }

  for (int i = 0; i < 9; i++) {
    for (int j = i + 1; j < 10; j++) {
      if (buffer_arr[i] > buffer_arr[j]) {
        temp = buffer_arr[i];
        buffer_arr[i] = buffer_arr[j];
        buffer_arr[j] = temp;
      }
    }
  }

  avgval = 0;
  for (int i = 2; i < 8; i++)
    avgval += buffer_arr[i];

  float volt = (float)avgval * 3.3 / 4095 / 6;
  float ph_act = -5.70 * volt + calibration_value;

  Serial.println("pH Value: ");
  Serial.println(ph_act);

  // Notification for pH
  if (ph_act < 6.8){
    String message = "ALERT pH level too low: " + String(ph_act);
    message += " - Time: " + getTimeStamp();
    bot.sendMessage(CHAT_ID, message);
  }else if (ph_act > 8.0){
    String message = "ALERT pH level too high: " + String(ph_act);
    message += " - Time: " + getTimeStamp();
    bot.sendMessage(CHAT_ID, message);
  }

  // Ultrasonic
  digitalWrite(trigPin, LOW);
  delayMicroseconds(2);
  digitalWrite(trigPin, HIGH);
  delayMicroseconds(10);
  digitalWrite(trigPin, LOW);

  duration = pulseIn(echoPin, HIGH);
  distance = duration / 58.2;
  String disp = String(distance);

  Serial.print("Distance: ");
  Serial.print(disp);
  Serial.println(" cm");

    // Notification for waterlevel
    if (distance > 5){
    String message = "ALERT water level too low: " + String (distance) + " cm";
    message += " - Time: " + getTimeStamp();
    bot.sendMessage(CHAT_ID, message);
  }else if (distance < 3){
    String message = "ALERT water level too high: " + String (distance) + " cm";
    message += " - Time: " + getTimeStamp();
    bot.sendMessage(CHAT_ID, message);
  }

//insert data to database
String postData = "temperature=" + String(tempC) + "&ph_act=" + String(ph_act) + "&distance=" + String(distance);
  
  HTTPClient http;
  http.begin(URL);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  
  int httpCode = http.POST(postData);
  String payload = http.getString();

  Serial.println("--------------------------------------------------");

  delay(1000);
  }


void connectWiFi() {
  WiFi.mode(WIFI_OFF);
  delay(1000);
  // This line hides the viewing of ESP as a wifi hotspot
  WiFi.mode(WIFI_STA);

  WiFi.begin(ssid, password);
  Serial.println("Connecting to WiFi");
  client.setCACert(TELEGRAM_CERTIFICATE_ROOT);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.print("Connected to: ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());

  bot.sendMessage(CHAT_ID, "System started");
}

String getTimeStamp() {
  time_t now = time(nullptr);

  now += 8 * 3600;

  struct tm *timeInfo = localtime(&now);
  char timestamp[20];
  strftime(timestamp, sizeof(timestamp), "%Y-%m-%d %H:%M:%S", timeInfo);
  return String(timestamp);
}