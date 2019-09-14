# Programa em Python criado para ser um cliente MQTT no broker
# ele assina o tópico esp/vazao e ao receber mensagens salva no arquivo registro.txt
# - 13/09/2019 	- Versão inicial
#				- Comentários

import paho.mqtt.client as mqtt # importa biblioteca Paho (Paho é uma biblioteca de MQTT para Python)

arquivo = open('registro.txt', 'r') # Abre o arquivo registro.txt em modo leitura
conteudo = arquivo.readlines() # Lê o arquivo e salva o conteudo na variável 'conteudo'
arquivo.close()


# Chamada para quando o cliente recebe a resposta CONNACK do servidor (indica que o cliente está conectado)
def on_connect(client, userdata, flags, rc):
    print("Conectado. Código: "+str(rc))
    # Subscrevendo dentro do on_connect() significa que se perdermos a conexão todas as subinscrições serão renovadas.
    client.subscribe("esp/vazao")

# Chamada de para quando uma mensagem PUBLISH é recebido do servidor (broker).
def on_message(client, userdata, msg):
    print(msg.topic+" "+str(msg.payload)) # Imprime na tela (console a mensagem)
    conteudo.append(msg.topic+"|"+str(msg.payload)+"\n") # acrescenta a linha ao conteúdo do arquivo (Ainda em memória)
    arquivo = open('registro.txt', 'w') # abre o arquivo como escrita / write (o python não faz 'append')
    arquivo.writelines(conteudo) # Escreve o conteúdo no arquivo (passa da memória para o arquivo)
    arquivo.close() # Fecha o arquivo

client = mqtt.Client(client_id="PythonCliAgua", clean_session=False) # Instancia o cliente MQTT
client.username_pw_set("agua", password="1368") # Parâmetros de conexao (usuário e senha)
client.on_connect = on_connect # aponta as função a ser chamada quando o evento 'on_connect' ocorrer
client.on_message = on_message # aponta as função a ser chamada quando o evento 'on_message' ocorrer

client.connect("192.168.64.100", 1883, 60) # Parâmetros de conexao (hostname/ip , porta e keepalive)

# Parte a ser editada
# Blocking call that processes network traffic, dispatches callbacks and
# handles reconnecting.
# Other loop*() functions are available that give a threaded interface and a
# manual interface.
client.loop_forever()
