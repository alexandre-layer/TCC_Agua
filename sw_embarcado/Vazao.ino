// definir porta de leitura do sensor de Vazao
// Versão inicial = 29/08/2019
//
// - Le vazão e envia via serial e MQTT
//mqtt

#include <NTPClient.h>//Biblioteca do NTP.
//#include <WiFiUDP.h>//Biblioteca do UDP.
#include <PubSubClient.h>
#include <WiFi.h>

const char* ssid = "AtlantisF";
const char* password = "13681368";
const char* mqttServer = "192.168.64.100";
const int mqttPort = 1883;
const char* mqttUser = "agua";
const char* mqttPassword = "1368";

WiFiClient espClient;
PubSubClient client(espClient);

WiFiUDP udp;
NTPClient ntp(udp, "a.st1.ntp.br", -3 * 3600, 60000); //Cria um objeto "NTP" com as configurações. 
String hora; //Váriavel que armazenara o horario do NTP.

//vazão
const int portaVazao = GPIO_NUM_35;
static void atualizaVazao();
volatile int pulsos_vazao = 0;
float vazao = 0;
String buffer;
// interrupção
void IRAM_ATTR gpio_isr_handler_up(void* arg)
{
  pulsos_vazao++;
  portYIELD_FROM_ISR();
}
 
/*
Inicializa sensor de vazão com interrupção na subida de um pulso
 */
void iniciaVazao(gpio_num_t Port){
  gpio_set_direction(Port, GPIO_MODE_INPUT);
  gpio_set_intr_type(Port, GPIO_INTR_NEGEDGE);
  gpio_set_pull_mode(Port, GPIO_PULLUP_ONLY);
  gpio_intr_enable(Port);
  gpio_install_isr_service(0);
  gpio_isr_handler_add(Port, gpio_isr_handler_up, (void*) Port);
}
 
void setup() {
  // inicializar serial
  Serial.begin(115200);
  WiFi.begin(ssid, password);
 
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.println("Conectando ao WiFi..");
  }
  //NTP

   ntp.begin();//Inicia o NTP.
   ntp.forceUpdate();//Força o Update.
   
  // MQTT
  client.setServer(mqttServer, mqttPort);
  while (!client.connected()) {
    Serial.println("Connecting to MQTT...");
 
    if (client.connect("ESP32Client", mqttUser, mqttPassword )) {
 
      Serial.println("connected");
 
    } else {
 
      Serial.print("failed with state ");
      Serial.print(client.state());
      delay(2000);
 
    }
  }
 
  client.publish("esp/test", "Hello from ESP32");
  
  // definir porta do sensor de vazão como entrada
  iniciaVazao((gpio_num_t) portaVazao);
}
 
void loop() {
  // Transformação dos pulsos para correspondente vazao
  //vazao = pulsos_vazao/5.5;
  vazao = pulsos_vazao;

  pulsos_vazao = 0;
  // Realizar o print da leitura no serial
  //Serial.println("Leitura do Sensor de Vazao:");
  //Serial.println(vazao);
  // realizar um delay e inicializar leitura daqui a 1 segundos
  //client.loop();
  hora = ntp.getFormattedTime();//Armazena na váriavel HORA, o horario atual.
  buffer = hora + "=" + String(vazao);
  client.publish("esp/vazao", (char*) buffer.c_str());
  delay(5000);
 
}
