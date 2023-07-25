#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "Barun"; //wifi ssid
const char* password = "R@isecom22"; //wifi password
//put your api url
String serverName = "https://rewon123.000webhostapp.com/";

// the following variables are unsigned longs because the time, measured in
// milliseconds, will quickly become a bigger number than can be stored in an int.
unsigned long lastTime = 0;
// Timer set to 10 minutes (600000)
//unsigned long timerDelay = 600000;
// Set timer to 5 seconds (5000)
unsigned long timerDelay = 5000;

void setup() {
  Serial.begin(9600);


  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
 
  Serial.println("Timer set to 5 seconds (timerDelay variable), it will take 5 seconds before publishing the first reading.");
}

void loop() {
  if (Serial.available()) {
    String payload = Serial.readStringUntil('\r\n');
    float cmd = payload.toFloat();
    // Serial.println(payload); //print the incoming data i.e 1 to nodemcu console 
    
    // parse data from Arduino
    //int id = random(1,9);
    // int humidityIndex = data.indexOf("H:") + 2;
    // int temperatureIndex = data.indexOf(";T:") + 3;
    // int weightIndex = payload.indexOf("W:");

    // float humidity = data.substring(humidityIndex, temperatureIndex - 3).toFloat();
    // float temperature = data.substring(temperatureIndex, soil_moistureIndex - 3).toFloat();
    // float weight = payload.substring(weightIndex).toFloat();
    // Serial.println(weight);
// Serial.println(weight);    

    //Send an HTTP POST request every 10 minutes
    if ((millis() - lastTime) > timerDelay) {
    //Check WiFi connection status
    if(WiFi.status()== WL_CONNECTED){
      HTTPClient http;
    //api_key=IBMMYSP7JQQWBHC6&field1=0
      String serverPath = serverName + "?weight=" + String(cmd); //modify as per your requirement for data processing
      Serial.println(serverPath);
      
      // Your Domain name with URL path or IP address with path
      http.begin(serverPath.c_str());
      
      // If you need Node-RED/server authentication, insert user and password below
      //http.setAuthorization("REPLACE_WITH_SERVER_USERNAME", "REPLACE_WITH_SERVER_PASSWORD");
      
      // Send HTTP GET request
      int httpResponseCode = http.GET();
      
      if (httpResponseCode>0) {
        Serial.print("HTTP Response code: ");
        Serial.println(httpResponseCode);
        
      }
      else {
        Serial.print("Error code: ");
        Serial.println(httpResponseCode);
      }
      // Free resources
      http.end();
    }
    else {
      Serial.println("WiFi Disconnected");
    }
    lastTime = millis();
  }
  }