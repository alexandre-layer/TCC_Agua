# Essa linha é para reconhecimento de acentos (pt-br) This Python file uses the following encoding: utf-8 
# Programa em Python criado para ser um cliente MQTT no broker
# Ele assina o tópico esp/vazao e ao receber mensagens salva no arquivo registro.txt
# - 13/09/2019 	- Versão inicial
#               - Comentários
#               - Cria o arquivo caso nao exista (registro.txt)
# - 15/09/2019  - Alterado método de abertura do arquivo de registro (append)
#               - Se o arquivo não existir cria novo ('a+')
# - 17/11/2019  - mudança para armazenamento das mensagens em banco de dados (MySQL)
# - 20/02/2020 - Mudanças nos bancos
#
# - 24/02/2020 - Mudança no nome do topico mqtt e na senha do bd#
# - 29/02/2020 - mudança na forma de inserir (mudança na estrutura do banco (PK Medidor))
# - 14/06/2020 - Mudança do import do SQL (Compatibilidade Python3)
#
import mysql.connector as mdb		# importa Mysql
import paho.mqtt.client as mqtt # importa biblioteca Paho (Paho é uma biblioteca de MQTT para Python)
servdb = "localhost"
topico = "medidor/#"

#chamada para conectar ao banco
con = mdb.connect(# Parametros do banco
  host=servdb,
  database='simcona', 
  user='aguasql', 
  password='pass1368'
)
cur = con.cursor()

#Obter configurações do broker a partir do banco
cur.execute("SELECT usuarioBroker,senhaBroker, enderecoBroker FROM Configuracao WHERE id=0")
configuracao = cur.fetchone()
usuariobroker = configuracao[0]
senhabroker = configuracao[1]
servbroker = configuracao[2]

# Chamada (função) para quando o cliente recebe a resposta CONNACK do servidor (indica que o cliente está conectado)
def on_connect(client, userdata, flags, rc):
    print("Conectado. Código: "+str(rc))
    # Subscrevendo dentro do on_connect() significa que se perdermos a conexão todas as subinscrições serão renovadas.
    client.subscribe(topico)

# Chamada (função) para quando uma mensagem PUBLISH é recebida do servidor (broker).
def on_message(client, userdata, msg):
    print(msg.topic+" = > "+str(msg.payload))           # Imprime na tela (console a mensagem)
    valor = str(msg.payload)			    # Recebe o tópico e a mensagem, concatenando em 'conteudo' 
    stmt = "INSERT INTO Registro(horario, valor, idMedidor) SELECT NOW(),'"+str(valor.split("=")[1])+", id FROM Medidor WHERE topico = '"+msg.topic+"'"
    cur.execute(stmt) # prepara insert
    con.commit() #commit da operação do insert

client = mqtt.Client(client_id="PythonCliAgua", clean_session=False) # Instancia o cliente MQTT
client.username_pw_set(usuariobroker, password=senhabroker) # Parâmetros de conexao (usuário e senha)
client.on_connect = on_connect # aponta as função a ser chamada quando o evento 'on_connect' ocorrer
client.on_message = on_message # aponta as função a ser chamada quando o evento 'on_message' ocorrer

client.connect(servbroker, 1883, 60) # Parâmetros de conexao (hostname/ip , porta e keepalive)

# Loop principal que mantem o cliente conectado e recebendo as mensagens
# Existem outros tipos de loop no Paho (suporte a 'threads', etc)
client.loop_forever() 

