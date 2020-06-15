# -*- coding: utf-8 -*-
"""
Created on Thu Jun 11 19:23:20 2020
Avaliador
11/06/2020 = Criação estrutural (primeira Versão)
11/06/2020 = Funções    recuperaMedidores()
                        recuperaRegistros(minutosAtras, medidor)           
                        avaliaRegistros(df, intpt, coef)
                        recuperaModeloAnotacoes(idmed, segmento, diaSemana)
13/06/2020 = Função     armazenaAlerta(dspaval)
             Funcional (Armazenando Alertas)
@author: Layer
"""
#imports
import mysql.connector as sql
from datetime import timedelta
from datetime import datetime
import pandas as pd
from sklearn.linear_model import LinearRegression

banco = sql.connect( # Parametros do banco
  host="localhost",
  database='simcona', 
  user='aguasql', 
  password='pass1368'
)
cursql = banco.cursor()

# Função Recupera medidores
def recuperaMedidores():
    cursql.execute("SELECT id, nome, topico FROM Medidor")
    resultadoMedidores = cursql.fetchall()
    return resultadoMedidores
#recupera registros
def recuperaRegistros(minutosAtras, medidor):
    inicio = str(datetime.now() - timedelta(minutes=minutosAtras))
    fim =str (datetime.now())
    #Select e where    
    dbsel = "UNIX_TIMESTAMP(horario) AS horario, valor"
    dbwhe = "idMedidor="+str(medidor)+" AND horario BETWEEN '"+inicio+"' AND '"+fim+"' ORDER BY id ASC"
    #execução
    df = pd.read_sql("SELECT "+dbsel+" FROM Registro WHERE "+dbwhe, con=banco)
    if df.empty:
        return pd.DataFrame()
    #Passando o tempo para absoluto (Calculos Epoch (delta))
    primeirohorario = df.loc[0, 'horario']
    df['horario'] = df['horario'].sub(primeirohorario)
    primeirohorario = df.loc[0, 'horario']
    #Cria terceira coluna
    df['acumulado'] = 0
    i=1
    while (i < len(df.index)):
       df['acumulado'][i] = df['acumulado'][i-1]+df['valor'][i]
       i +=1
    return df
#avalia registros
def avaliaRegistros(df, intpt, coef): # Dataframe, coef, intercept
    #pega os parâmetros do df
    x = df.iloc[:,:1].values
    y = df.iloc[:,2:3].values
    #instancia o regressor
    regressor = LinearRegression()
    regressor.fit(x,y)
    regressor.intercept_ = intpt
    regressor.coef_[0] = coef
    #monta o regressor com os parâmetros enviados na função
    derror = y - regressor.predict(x)
    percent = 0
    soma=0
    contaerro=0
    totalleitura = len(derror)
    for leitura in derror:
        soma +=leitura
        if leitura>=0:
            contaerro += 1
    percent = (100*contaerro)/totalleitura
    # Porcentagem de erro Positivo: percent / Soma do erro: soma    
    retornoDesempenho = [soma[0],percent]
    return retornoDesempenho

def recuperaModeloAnotacoes(idmed, segmento, diaSemana):
    if diaSemana < 5: diaSemSTR = "< 5"
    else: diaSemSTR = "> 4"
    dbsel = "SELECT ModeloAnotacao.id, ModeloAnotacao.intpt, ModeloAnotacao.coef, Anotacao.id FROM ModeloAnotacao "
    dbon = "INNER JOIN Anotacao ON Anotacao.id=ModeloAnotacao.idAnotacao "
    dbwhe = "WHERE Anotacao.idMedidor = "+str(idmed)+" AND ModeloAnotacao.diaSemana "+diaSemSTR+" AND ModeloAnotacao.segHora = "+str(segmento)#
    cursql.execute(dbsel + dbon + dbwhe)
    print(dbsel + dbon + dbwhe)
    resultadomodelos = cursql.fetchall()
    return resultadomodelos # idModeloanotacao, intpt, coef
def  armazenaAlerta(dspaval):
    peso = 0
    if dspaval[3] > 50.00: peso +=50
    if dspaval[2] > 1: peso +=50
    valores = "'Cons. Anormal','"+str(datetime.now())+"',"+str(dspaval[4])+","+str(dspaval[0])+","+str(peso)
    cursql.execute("INSERT INTO Alerta (textoDescricao, horario, idAnotacao, idMedidor, peso) VALUES ("+valores+")") 
    banco.commit()

#"MAIN"
medidores = recuperaMedidores()
segmentoAtual = (datetime.now().hour //3)
desempenhos = []
modelos = []
dfmedidores = []
mdmedidores = []
est_totalmodelos = 0
est_totalalertas = 0

for medidor in medidores: #iteração de medidores
    dataframedomedidor = recuperaRegistros(15, medidor[0])
    dfmedidores.append(dataframedomedidor)
    modelosAnotacoes = recuperaModeloAnotacoes(medidor[0], segmentoAtual,datetime.now().weekday())
    mdmedidores.append(modelosAnotacoes)
    if not(dataframedomedidor.empty): 
        for modelo in modelosAnotacoes:
            est_totalmodelos +=1
            retornoAvalia = avaliaRegistros(dataframedomedidor, float(modelo[1]) , float(modelo[2]))
            resultadodesempenho = [medidor[0],modelo[0],retornoAvalia[0], retornoAvalia[1],modelo[3]]
            desempenhos.append(resultadodesempenho)
for desempenhoavaliado in desempenhos:
    if desempenhoavaliado[2] > 1 or desempenhoavaliado[3] > 50.00:
        armazenaAlerta(desempenhoavaliado)
        est_totalalertas +=1
print ("Estatisticas gerais")    
print ("Foram processados um total de "+str(est_totalmodelos)+" modelos")
print ("Foram armazenados um total de : "+str(est_totalalertas)+ " alertas")

