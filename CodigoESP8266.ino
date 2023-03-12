

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>


const char* ssid = "ubnt";
const char* password = "********";
const char* serverName = "http://arturmiranda.online/definitivo/monitoramentoespPHPv2.php";
int httpResponseCode;
String apiKeyValue = "AXYLeagmiaYFo";
const int input = 4;
volatile unsigned long tempo_atual, tempo_anterior = 0;
volatile float vazao, f, T, consumo = 0;
unsigned long t = 0, t1 = 0;
WiFiClient client;
HTTPClient http;

ICACHE_RAM_ATTR void detectPulse() {

  tempo_atual = micros();
  T = ((float)tempo_atual - (float)tempo_anterior) / 1000000;
  f = 1 / T;
  vazao = 3.63636 + 7.27273 * f;
  consumo = consumo + (vazao / 3600) / f;
  tempo_anterior = tempo_atual;
}

void conectar() {

  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
}

void postar(String apiKeyValue, float consumo) {

  http.begin(client, serverName);
  http.addHeader("Content-Type", "application/json");
  
  String httpRequestData = "{\"api_key\":\"" + apiKeyValue + "\",\"sensor\":\"Fluxo\",\"localizacao\":\"Casa\", \"consumo\":\"" + consumo + "\"}";

  Serial.print("httpRequestData: ");
  Serial.println(httpRequestData);
  httpResponseCode = http.POST(httpRequestData);
  if (httpResponseCode > 0) {
    Serial.print("HTTP Response code: ");
    Serial.println(httpResponseCode);
  } else {
    Serial.print("Error code: ");
    Serial.println(httpResponseCode);
  }
  http.end();
}




void setup() {
  Serial.begin(115200);
  conectar();

  attachInterrupt(digitalPinToInterrupt(input), detectPulse, RISING);
}


void loop() {

  if (millis() - t1 > 1000 * 60 * .333) {

    t1 = millis();
    if (WiFi.status() == WL_CONNECTED) {
      postar(apiKeyValue, consumo * 1000);
      while (httpResponseCode != 200) {
      postar(apiKeyValue, consumo * 1000);
      }

    } else {
      Serial.println("WiFi Disconnected");
    }

    consumo = 0;
  }
}
