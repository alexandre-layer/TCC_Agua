# -*- coding: utf-8 -*-
"""
Created on Thu May 28 14:20:44 2020
Modelador
18/06/2020 = criação estrutural (núcleo)
08/06/2020 = Funçoes   recuperaMedidores()
                       recuperaAnotacoes(idmed) 
                       calculatotalsegmentos(anotcalc)
                       marcaModelada(idanot)  
09/06/2020 = Funções   segmento_calc(segmento)
                       segmentosSQL(anotseg)
                       processamodelo(paramproc)
                       armazenamodelo(paramod)
11/06/2020 = Primeira limpeza no código (ainda há detalhes a melhorar)                       
@author: Layer
"""

import mysql.connector as sql
from datetime import timedelta
import datetime
import pandas as pd
from sklearn.linear_model import LinearRegression

banco = sql.connect( # Parametros do banco
  host="localhost",
  database='simcona', 
  user='aguasql', 
  password='pass1368'
)
cursql = banco.cursor()
    

# Função pra recuperar medidores do banco
def recuperaMedidores():
    cursql.execute("SELECT id, nome, topico FROM Medidor")
    resultadoMedidores = cursql.fetchall()
    return resultadoMedidores
# Função pra recuperar anotações do banco
def recuperaAnotacoes(idmed):
    cursql.execute("SELECT id, horaInicio, horaFim, tipoAnotacao, idMedidor FROM Anotacao WHERE modelado = false and idMedidor ="+str(idmed))
    resultadoanotacoes = cursql.fetchall()
    return resultadoanotacoes
# Função que marca anotação como modelada
def marcaModelada(idanot):
    cursql.execute("UPDATE Anotacao SET modelado= true WHERE id = "+str(idanot))
    banco.commit()
    return
#função que determina número total de segmentos
def calculatotalsegmentos(anotcalc):
    inicio = anotcalc[1]
    fim = anotcalc[2]
    #timedelta só retorna segundos e dias.
    diferenca = fim - inicio
    diferenca_dias = diferenca.days
    #calculo total de segmentos no periodo
    total_segmentos = ((diferenca_dias *24) + (diferenca.seconds //3600)) // 3
    if (diferenca.seconds %3600 >0): total_segmentos +=1
    return total_segmentos
#Função calculadora de segmentos (retorna hora inicial e final dado um segmento)
def segmento_calc(segmento):
    horaini = str("%02d" % (segmento*3,))+":00:00"
    horafim = str("%02d" % (((segmento+1)*3)-1))+":59:59"
    respostahora = {'inicio': horaini,'fim' : horafim}
    return respostahora

#Função que cria os parêmetros dos segmentos para as queries SQL
def segmentosSQL(anotseg):
    # Inicialização de variaveis
    inicio = anotseg[1]
    fim = anotseg[2]
    diferenca_dias = (fim - inicio).days
    seghoraini = (inicio.hour //3)
    diaatual = 0
    segatual = seghoraini
    segatual_total = calculatotalsegmentos(anotseg)
    dt_atual = inicio
    retornoSQL = list()
    #loop while
    while (segatual_total > 0) and (diaatual<=diferenca_dias):
        h_seg = segmento_calc(segatual)
        if (diaatual ==0 and datetime.datetime.strptime(h_seg['inicio'], "%H:%M:%S").time() < inicio.time() ):
            sql_inicio = str(dt_atual)
        else:
            sql_inicio = str(inicio.date() + timedelta(days=diaatual))+" " +h_seg['inicio']
        if (diaatual==diferenca_dias and datetime.datetime.strptime(h_seg['fim'], "%H:%M:%S").time() > fim.time()):
            sql_fim= str(fim)
        else:
            sql_fim= str(inicio.date() + timedelta(days=diaatual))+" " +h_seg['fim']
        sql_d_sem = int(datetime.datetime.fromisoformat(sql_inicio).weekday())
        montada = [anotseg[0],anotseg[4],sql_inicio,sql_fim, segatual,sql_d_sem ]
        retornoSQL.append(montada)
        #INCs e DECs
        segatual_total -=1
        if (segatual == 7):
            segatual =0
            diaatual += 1
        else: segatual +=1
    return retornoSQL

def processamodelo(paramproc): #Retorna [idAnotacao, idmedidor, diasemana, segmnto,intpt,coef]
    inicio = paramproc[2]
    fim = paramproc[3]
    #select e where SQL
    dbsel = "id, UNIX_TIMESTAMP(horario) AS horario, valor"
    dbwhe = "idMedidor="+str(paramproc[1])+" AND horario BETWEEN '"+inicio+"' AND '"+fim+"' ORDER BY id ASC"
    #execução
    #Carregando via MySQL
    df = pd.read_sql("SELECT "+dbsel+" FROM Registro WHERE "+dbwhe, con=banco)
    #localiza o primeiro horario para fazer o delta (iniciar em zero), se não for nulo
    if df.empty:
        #print ("Dataframe Vazio! Abortando")
        return 
    #Calculos Epoch (delta)
    primeirohorario = df.loc[0, 'horario']
    linhas = len(df.index)
    df['horario'] = df['horario'].sub(primeirohorario)
    primeirohorario = df.loc[0, 'horario']
    #Rotina para fazer o acumulado
    df['acumulado'] = 0
    i=1
    while (i < linhas):
       df['acumulado'][i] = df['acumulado'][i-1]+df['valor'][i]
       i +=1
    x = df.iloc[:,1:2].values
    y = df.iloc[:,3:4].values
    x = x.reshape(-1,1)
    regressor = LinearRegression()
    regressor.fit(x,y)
    return [paramproc[0],paramproc[1],paramproc[5],paramproc[4],regressor.intercept_ ,regressor.coef_[0]]    
    
def armazenaModelo(paramod):
    valores = str(paramod[0])+","+str(paramod[2])+","+str(paramod[3])+","+str(paramod[4][0])+","+str(paramod[5][0])
    cursql.execute("INSERT INTO ModeloAnotacao (idAnotacao, diaSemana, segHora,intpt,coef) VALUES ("+valores+")") 
    #print("INSERT INTO ModeloAnotacao (idAnotacao, diaSemana, segHora,intpt,coef) VALUES ("+valores+")") 
    banco.commit()
    return

# "MAIN"
est_totalsegmentos = 0
est_totalmedidores = 0
est_totalanotacoes = 0  

medidores = recuperaMedidores() # obtem medidores do banco
naomodeladas = list() #instanciando a lista de não modeladas
for medidor in medidores: # pega todos os medidores
    # Recupera anotacoes ainda não modeladas do medidor
    anotacoesrecuperadas = recuperaAnotacoes(medidor[0])
    est_totalmedidores +=1
    # Percorre todas as anotações recuperadas e vai acrescentandoa lista
    for anotacao in anotacoesrecuperadas:
        naomodeladas.append(anotacao)
    
#pega todas as anotações e transforma em modelo, uma por uma (sequencial)
segmentos = []
parametros = []
for anotacao in naomodeladas:  
    retornoSegmentosSQL = segmentosSQL(anotacao)
    if retornoSegmentosSQL:
        for segmentoSQL in retornoSegmentosSQL: #enche lista com todos os dados para modelo
            segmentos.append(segmentoSQL)
    
        for parametrosmodelo in segmentos: # itera lista de segmentos para cada modelo e salva
            est_totalsegmentos +=1
            retornoProcessa = processamodelo(parametrosmodelo)
            if retornoProcessa: #se não for nulo / vazio
                parametros.append(retornoProcessa)
                armazenaModelo(retornoProcessa)
    marcaModelada (anotacao[0])   #Marca essa anotacao como modelada
    est_totalanotacoes +=1
print ("Estatisticas gerais")    
print ("Foram processados um total de "+str(est_totalsegmentos)+" segmentos")
print ("Total de medidores: "+str(est_totalmedidores)+", total de anotacoes: "+str(est_totalanotacoes))
