// Versão inicial = 29/08/2019
//
// 29/08/2019 - Le vazão e envia via serial e MQTT
// 13/09/2019 - Limpeza do código e comentários adicionais
//            - Comentei as partes de mensagens via serial (mantive para usar em caso de 'debug' futuramente)
//            - Alterei o servidor NTP (estou usando o instalei um NTP no raspberry / broker)
//            - O led Azul embutido do ESP32 faz uma sinalização de mensagem transmitida
// 28/01/2020 - Limpeza do código, alteração de variaveis e inclusão de constantes para configuração
// 13/02/2020 - Mudança do ID do cliente para evitar conflito entre os varios medidores
//            - Mudança do nome da variável de tópico
// Importação de bibliotecas
#include <NTPClient.h>    // Biblioteca do NTP.
#include <PubSubClient.h> // Biblioteca MQTT
#include <WiFi.h>         // Biblioteca WiFi

// Parâmetros WiFi
const char* ssid = "AtlantisF";     //SSID (Nome da rede)
const char* password = "13681368";  // Senha

//Parâmetros MQTT
const char* mqttServer = "192.168.64.100";    // IP / Hostname do Broker
const int mqttPort = 1883;                    // porta
const char* mqttUser = "agua";                // Usuário
const char* mqttPassword = "1368";            // Senha
const char* idMedidor = "Medidor02";          // Id do medidor
const char* topicoMedidor = "medidor/esp32b"; // topico do medidor

//Parâmetro NTP
const char* ntpServer = "192.168.64.100";

// Instâncias Principais
WiFiClient espClient;                                   // Wifi (principal usada pelo MQTT
PubSubClient client(espClient);                         // MQTT
WiFiUDP udp;                                            // UDP - Usada pelo NTP
NTPClient ntp(udp, ntpServer, -3 * 3600, 60000);   // Cria um objeto "NTP" com as configurações. 

// Variáveis 
String hora;                           // Váriavel para armazenar o horario do NTP.
String buffer;                         // Variável para concatenar String que vai ser enviada no payload do MQTT.                         
float vazao = 0;                       // Vazão traduzida (de pulso para Litros, m3, etc) a ser enviada na mensagem.
volatile int pulsos_vazao = 0;         // Variável que armazena o número de pulsos contados no período de medição.
int periodo = 5000;                    // Variável do tempo entre leituras (periodo) em milisegundos

// Parâmetros de hardware
const int portaSensor = GPIO_NUM_35;   // Define o pino (GPIO) que vai ser usado para receber os pulsos de vazão.
const int LED_ENVIO = 2;                   // Pino do LED azul embutido na placa do ESP (sinaliza msg enviada)

void IRAM_ATTR gpio_isr_handler_up(void* arg) // Função da interrupção. Esssa função interrompe o programa principal a cada vez que o sensor da um pulso.
{
  pulsos_vazao++;         // Incrementa o pulso
  portYIELD_FROM_ISR();   // Força retorno ao programa principal. Sem essa função pode haver retardo para retorno a 'main' pois depende de um 'tick' do timer principal.
}
 
void iniciaSensor(gpio_num_t Port) {                // Função para inicializar a porta do sensor
  gpio_set_direction(Port, GPIO_MODE_INPUT);                        // Coloca a porta em modo input (entrada)
  gpio_set_intr_type(Port, GPIO_INTR_NEGEDGE);                      // Escolhe tipo de interrupção (interrompe na descida do pulso. Ex: de 1 para 0)
  gpio_set_pull_mode(Port, GPIO_PULLUP_ONLY);                       // PullUp (tipo de conexão eletronica)
  gpio_intr_enable(Port);                                           // Habilita interrupção na porta
  gpio_install_isr_service(0);                                      // Serviço de manipulação de interrupção
  gpio_isr_handler_add(Port, gpio_isr_handler_up, (void*) Port);    // Coloca a porta no driver de interrupção
}
 
void setup() {

  // inicializar serial (serial não esta sendo mais usada nesse código mas vou manter comentada para caso de 'debug')
  // Serial.begin(115200);
  pinMode(LED_ENVIO, OUTPUT);                   // Seta o pino do LED como output
  WiFi.begin(ssid, password);                     // Inicializa Wifi
 
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    // Serial.println("Conectando ao WiFi..");
  }
  //NTP
  ntp.begin();                                    //Inicia o NTP.
  ntp.forceUpdate();                              //Força o Update.
   
  // MQTT
  client.setServer(mqttServer, mqttPort); // parâmetros MQTT
  while (!client.connected()) {
    
    // Serial.println("Connecting to MQTT...");
    if (client.connect(idMedidor, mqttUser, mqttPassword )) { // conecta  MQTT
 
      // Serial.println("connected");
 
    } else {
       // Serial.print("failed with state ");
       // Serial.print(client.state());
      delay(2000);
    }
  }
  // Chama função para inicializar sensor 
  iniciaSensor((gpio_num_t) portaSensor);
}
 
void loop() {
  // Transformação dos pulsos para correspondente vazao (pulsos para litros, m3, etc)
  // exemplo: vazao = pulsos_vazao/5.5
  // por enquanto estou usando relação unitária (1:1. Podemos calibrar posteriormente
  vazao = pulsos_vazao;
  
  pulsos_vazao = 0; // Zera leitura de 
  
  // Serial.println("Leitura do Sensor de Vazao:");
  // Serial.println(vazao);
  
  hora = ntp.getFormattedTime();                          // Receber e Armazena na váriavel HORA, o horario atual.
  buffer = hora + "=" + String(vazao);                    // Concatena hora e vazão na variável buffer 
  client.publish(topicoMedidor, (char*) buffer.c_str());    // Publica  o conteúdo de buffer (Obs: a biblioteca recebe em char)
  digitalWrite(LED_ENVIO, HIGH);
  delay(200);
  digitalWrite(LED_ENVIO, LOW);
  delay(periodo - 200);   // delay (enquanto isso o sensor está armazenando os pulsos a serem enviados no próximo loop)
  client.loop(); // Verificar se isso ainda é necessário
 
}
