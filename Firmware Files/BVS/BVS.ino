/*
  AUTHOR:   Fahim Faisal (embeddedfahim)
  DATE:     September 18, 2020
  LICENSE:  Apache 2.0 (Public Domain)
  CONTACT:  embeddedfahim@gmail.com
*/

#include<ESP8266WiFi.h> // library for ESP8266's on-board Wi-Fi module..
#include<SoftwareSerial.h> // library for emulating serial communication on non-dedicated pins..
#include<ESP8266HTTPClient.h> // library for dealing with HTTP requests and responses..
#include<Adafruit_Fingerprint.h> // library for driving the R307 fingerprint sensor..

// pins
int softRX = D2;
int softTX = D3;
int green_led = D6;
int red_led = D7;

// global variables
uint8_t id;
int deleteID, devID;
String msg, link, link2;
String serverURL = "http://bvs.embeddedfahim.xyz"; 
const char *ssid = "EMBEDDEDFAHIM";
const char *password = "ubuntu11";

// objects
HTTPClient http;

// software/emulated serial communication
SoftwareSerial mySerial(softRX, softTX);
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);

void setup() {
  Serial.begin(9600); // commence serial communication with PC/UART device at 9600 bps..
  
  // pins initialization
  pinMode(red_led, OUTPUT);
  pinMode(green_led, OUTPUT);
  
  // Wi-Fi initialization
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  WiFi.hostname("BVS1");

  // try to connect to the saved wireless network..
  while(WiFi.status() != WL_CONNECTED) {
    digitalWrite(red_led, HIGH);
    delay(500);
    digitalWrite(red_led, LOW);
  }

  // if successfully connected to Wi-Fi, flash the green LED to indicate that..
  Serial.println("Connected to Wi-Fi..");
  digitalWrite(green_led, HIGH);
  delay(250);
  digitalWrite(green_led, LOW);
  
  // try to commence software serial communication with the fingerprint sensor..
  finger.begin(57600);
  delay(5);

  if(finger.verifyPassword()) {
    Serial.println("Found fingerprint sensor..");
  }
  else {
    // loop forever so that the MCU will reset itself at some point..
    while(1) {
      Serial.println("Did not find fingerprint sensor!!");
      digitalWrite(red_led, HIGH);
      delay(250);
      digitalWrite(red_led, LOW);
    }
  }
}

void loop() {
  checkMode();
}

void checkMode() {
  String operationMode;
  
  link = serverURL + String("/get_mode.php");
  http.begin(link);
  int httpCode = http.GET();

  if(httpCode == 200) {
    operationMode = http.getString();
  }
  else {
    Serial.println("An error has occured!! Retrying..");
    checkMode();
  }

  if(operationMode == "0") {
    // do nothing..
  }
  else if(operationMode == "1") {
    id = getDevID();
    getFingerprintEnroll();
    checkMode();
  }
  else if(operationMode == "2") {
    getFingerprintID();
    delay(250);
    checkMode();
  }
  else if(operationMode == "3") {
    deleteFingerprint();
    checkMode();
  }
}

uint8_t getDevID() {
  link = serverURL + String("/get_devid.php");
  http.begin(link);
  int httpCode = http.GET();

  if(httpCode == 200) {
    String payload = http.getString();
    devID = payload.toInt();
  }
  else {
    Serial.println("An error has occured!! Retrying..");
    getDevID();
  }
  
  http.end();
  return devID;
}

uint8_t getDeleteID() {
  link = serverURL + String("/get_deleteid.php");
  http.begin(link);
  int httpCode = http.GET();

  if(httpCode == 200) {
    String payload = http.getString();
    deleteID = payload.toInt();
  }
  else {
    Serial.println("An error has occured!! Retrying..");
    getDeleteID();
  }
  
  http.end();
  return deleteID;
}

void uploadMessage(String str) {
  link = serverURL + String("/set_msg.php?msg=") + str;
  link.replace(" ", "%20");
  http.begin(link);
  int httpCode = http.GET();
  
  if(httpCode == 200) {
    Serial.println("Successfully uploaded message to the remote web server..");
  }
  else {
    Serial.println("An error has occurred!! Retrying..");
    uploadMessage(msg);
  }

  delay(100);
}

uint8_t getFingerprintEnroll() {
  int p = -1;

  msg = "Waiting for valid finger to enroll on device as ID" + String(id);
  uploadMessage(msg);
  
  while(p != FINGERPRINT_OK) {
    p = finger.getImage();
    switch(p) {
      case FINGERPRINT_OK:
        msg = "Image taken..";
        uploadMessage(msg);
        break;
      case FINGERPRINT_NOFINGER:
        msg = "Waiting for finger..";
        uploadMessage(msg);
        break;
      case FINGERPRINT_PACKETRECIEVEERR:
        msg = "Communication error!!";
        uploadMessage(msg);
        break;
      case FINGERPRINT_IMAGEFAIL:
        msg = "Imaging error!!";
        uploadMessage(msg);
        break;
      default:
        msg = "Unknown error!!";
        uploadMessage(msg);
        break;
    }
  }

  p = finger.image2Tz(1);
  
  switch(p) {
    case FINGERPRINT_OK:
      msg = "Image converted..";
      uploadMessage(msg);
      break;
    case FINGERPRINT_IMAGEMESS:
      msg = "Image too messy!!";
      uploadMessage(msg);
      return p;
    case FINGERPRINT_PACKETRECIEVEERR:
      msg = "Communication error!!";
      uploadMessage(msg);
      return p;
    case FINGERPRINT_FEATUREFAIL:
      msg = "Could not find fingerprint features!!";
      uploadMessage(msg);
      return p;
    case FINGERPRINT_INVALIDIMAGE:
      msg = "Could not find fingerprint features!!";
      uploadMessage(msg);
      return p;
    default:
      msg = "Unknown error!!";
      uploadMessage(msg);
      return p;
  }
  
  msg = "Remove finger..";
  uploadMessage(msg);
  delay(2000);
  p = 0;
  
  while(p != FINGERPRINT_NOFINGER) {
    p = finger.getImage();
  }
  
  msg = "ID " + String(id);
  uploadMessage(msg);
  p = -1;
  msg = "Place same finger again..";
  uploadMessage(msg);
  
  while(p != FINGERPRINT_OK) {
    p = finger.getImage();
    switch(p) {
      case FINGERPRINT_OK:
        msg = "Image taken..";
        uploadMessage(msg);
        break;
      case FINGERPRINT_NOFINGER:
        msg = "Waiting for finger..";
        uploadMessage(msg);
        break;
      case FINGERPRINT_PACKETRECIEVEERR:
        msg = "Communication error!!";
        uploadMessage(msg);
        break;
      case FINGERPRINT_IMAGEFAIL:
        msg = "Imaging error!!";
        uploadMessage(msg);
        break;
      default:
        msg = "Unknown error!!";
        uploadMessage(msg);
        break;
    }
  }

  p = finger.image2Tz(2);
  
  switch(p) {
    case FINGERPRINT_OK:
      msg = "Image converted..";
      uploadMessage(msg);
      break;
    case FINGERPRINT_IMAGEMESS:
      msg = "Image too messy!!";
      uploadMessage(msg);
      return p;
    case FINGERPRINT_PACKETRECIEVEERR:
      msg = "Communication error!!";
      uploadMessage(msg);
      return p;
    case FINGERPRINT_FEATUREFAIL:
      msg = "Could not find fingerprint features!!";
      uploadMessage(msg);
      return p;
    case FINGERPRINT_INVALIDIMAGE:
      msg = "Could not find fingerprint features!!";
      uploadMessage(msg);
      return p;
    default:
      msg = "Unknown error!!";
      uploadMessage(msg);
      return p;
  }

  msg = "Creating model for on-device ID " + String(id);
  uploadMessage(msg);
  
  p = finger.createModel();
  
  if(p == FINGERPRINT_OK) {
    msg = "Prints matched..";
    uploadMessage(msg);
  }
  else if(p == FINGERPRINT_PACKETRECIEVEERR) {
    msg = "Communication error!!";
    uploadMessage(msg);
    return p;
  }
  else if(p == FINGERPRINT_ENROLLMISMATCH) {
    msg = "Fingerprints did not match!!";
    uploadMessage(msg);
    return p;
  }
  else {
    msg = "Unknown error!!";
    uploadMessage(msg);
    return p;
  }   

  msg = "Fingerprint of on-device ID " + String(id) + " ";
  uploadMessage(msg);
  p = finger.storeModel(id);

  if(p == FINGERPRINT_OK) {
    msg = "Stored..";
    uploadMessage(msg);
    checkMode();
  }
  else if(p == FINGERPRINT_PACKETRECIEVEERR) {
    msg = "Communication error!!";
    return p;
  }
  else if(p == FINGERPRINT_BADLOCATION) {
    msg = "Could not store in that location!!";
    return p;
  }
  else if(p == FINGERPRINT_FLASHERR) {
    msg = "Error writing to flash!!";
    return p;
  }
  else {
    msg = "Unknown error!!";
    return p;
  }
}

int getFingerprintID() {
  uint8_t p = finger.getImage();
  
  if(p != FINGERPRINT_OK) {
    return -1;
  }

  p = finger.image2Tz();
  
  if(p != FINGERPRINT_OK) {
    digitalWrite(red_led, HIGH);
    delay(250);
    digitalWrite(red_led, LOW);
    
    return -1;
  }

  p = finger.fingerFastSearch();
  
  if(p != FINGERPRINT_OK) {
    digitalWrite(red_led, HIGH);
    delay(250);
    digitalWrite(red_led, LOW);
    
    return -1;
  }
  
  digitalWrite(green_led, HIGH);
  delay(250);
  digitalWrite(green_led, LOW);
  uploadDevID(finger.fingerID);
}

void uploadDevID(int devid) {
  link = serverURL + String("/set_devid.php?devid=") + devid;
  http.begin(link);
  int httpCode = http.GET();

  if(httpCode == 200) {
    // flash green LED to indicate success..
    digitalWrite(green_led, HIGH);
    delay(250);
    digitalWrite(green_led, LOW);
  }
  else {
    // flash the red LED to indicate error..
    digitalWrite(red_led, HIGH);
    delay(250);
    digitalWrite(red_led, LOW);
  }

  http.end();
}

uint8_t deleteFingerprint() {
  uint8_t p = -1;
  
  deleteID = getDeleteID();
  p = finger.deleteModel(deleteID);

  if(p == FINGERPRINT_OK) {
    msg = "Deleted from device..";
    uploadMessage(msg);
    checkMode();
  }
  else if(p == FINGERPRINT_PACKETRECIEVEERR) {
    msg = "Communication error!!";
    uploadMessage(msg);
    return p;
  }
  else if(p == FINGERPRINT_BADLOCATION) {
    msg = "Could not delete in that location!!";
    uploadMessage(msg);
    return p;
  }
  else if(p == FINGERPRINT_FLASHERR) {
    msg = "Error writing to flash!!";
    uploadMessage(msg);
    return p;
  }
  else {
    msg = "Unknown error!!";
    uploadMessage(msg);
    return p;
  }
}
