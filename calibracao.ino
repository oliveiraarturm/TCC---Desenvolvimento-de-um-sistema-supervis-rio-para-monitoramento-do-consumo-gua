

// Set GPIOs for LED and PIR Motion Sensor

const int input = 4;
volatile unsigned long tempo_atual, tempo_anterior = 0; 
volatile float vazao, f, T, consumo = 0; 
volatile unsigned long t=0;


ICACHE_RAM_ATTR void detectPulse() {

   tempo_atual = micros(); 
   T = ((float)tempo_atual - (float)tempo_anterior)/1000000; 
   f =   1/T;
   vazao = 3.63636 + 7.27273*f;
   consumo = consumo +  (vazao/3600)/f ;
   tempo_anterior = tempo_atual;
   
   }

void setup() {
  Serial.begin(115200);
  attachInterrupt(digitalPinToInterrupt(input), detectPulse, RISING);

}

void loop() {

if (millis()-t > 2000){
  t=millis();
  Serial.printf( "consumo: %f\n", consumo*1000);  
  Serial.printf( "vazao: %f\n", vazao);
  Serial.println();
}
}
