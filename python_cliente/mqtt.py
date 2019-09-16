# Essa linha é para reconhecimento de acentos (pt-br) This Python file uses the following encoding: utf-8 
# Programa em Python criado para ser um cliente MQTT no broker
# Ele assina o tópico esp/vazao e ao receber mensagens salva no arquivo registro.txt
# - 13/09/2019 	- Versão inicial
#               - Comentários
#               - Cria o arquivo caso nao exista (registro.txt)
# - 15/09/2019  - Alterado método de abertura do arquivo de registro (append)
#               - Se o arquivo não existir cria novo ('a+') 
#

import paho.mqtt.client as mqtt # importa biblioteca Paho (Paho é uma biblioteca de MQTT para Python)

# Chamada (função) para quando o cliente recebe a resposta CONNACK do servidor (indica que o cliente está conectado)
def on_connect(client, userdata, flags, rc):
    print("Conectado. Código: "+str(rc))
    # Subscrevendo dentro do on_connect() significa que se perdermos a conexão todas as subinscrições serão renovadas.
    client.subscribe("esp/vazao")

# Chamada (função) para quando uma mensagem PUBLISH é recebida do servidor (broker).
def on_message(client, userdata, msg):
    print(msg.topic+" = > "+str(msg.payload))           # Imprime na tela (console a mensagem)
    conteudo = (msg.topic+"|"+str(msg.payload)+"\n")    # Recebe o tópico e a mensagem, concatenando em 'conteudo' 
    arquivo = open('registro.txt', 'a+')                # abre o arquivo como escrita / append
    arquivo.writelines(conteudo)                        # Escreve 'conteudo' no arquivo
    arquivo.close()                                     # Fecha o arquivo

client = mqtt.Client(client_id="PythonCliAgua", clean_session=False) # Instancia o cliente MQTT
client.username_pw_set("agua", password="1368") # Parâmetros de conexao (usuário e senha)
client.on_connect = on_connect # aponta as função a ser chamada quando o evento 'on_connect' ocorrer
client.on_message = on_message # aponta as função a ser chamada quando o evento 'on_message' ocorrer

client.connect("192.168.64.100", 1883, 60) # Parâmetros de conexao (hostname/ip , porta e keepalive)

# Loop principal que mantem o cliente conectado e recebendo as mensagens
# Existem outros tipos de loop no Paho (suporte a 'threads', etc)
client.loop_forever() 
