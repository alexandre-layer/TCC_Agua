function retornaUltimaLeitura(medidor, ponteiro) {
	$.post("cntrl/registro.php",
		{
		parametro1:medidor,
		funcao:"ultimaLeitura",
		})
	.done(function(data,status)
		{
		resposta = JSON.parse(data)
		gauges[ponteiro].updateGauge(resposta.valor)
		})
	return resposta.valor;
}
function acrescentaLista(alertasJSON)
{
	if (alertasJSON.id != null)
		{
		for (i = 0; i < ((alertasJSON.id).length); i++) 
			{ 
			textoAlerta =  alertasJSON.horario[i]+" = Medidor: "+alertasJSON.idMedidor[i]+" = "+alertasJSON.textoDescricao[i];
			$("#listAlertas").append("<option value=1>"+textoAlerta+"</option>");
			ultimoAlerta = alertasJSON.id[i]
			}
		}
}
function atualizaAlertas() {
	$.post("cntrl/alerta.php",
		{
		parametro1:ultimoAlerta,
		funcao:"retornaAlertas",
		})
	.done(function(data,status)
		{
		respAlertas = JSON.parse(data)
		acrescentaLista(respAlertas);
		})
}
$(document).ready(function(){
$.post("cntrl/medidor.php",
    {
       funcao:"listaMedidores",
    },
    function(data,status){
      resposta = JSON.parse(data)
	  medidoresId = resposta.id;
	});
	ultimoAlerta=-1;
	atualizaAlertas();	
});
	
var gauges  = []
		document.addEventListener("DOMContentLoaded", function(event) {
			var opt = {
				gaugeRadius : 100,
                minVal : 0,
                maxVal : 100,
                needleVal : Math.round(0),
                tickSpaceMinVal : 1,
                tickSpaceMajVal : 10, 
                divID : "gaugeBox1", 
                gaugeUnits : "%"
			} 
            gauges[0] = new drawGauge(opt);
			opt = {
				gaugeRadius : 100,
                minVal : 0,
                maxVal : 100,
                needleVal : Math.round(0),
                tickSpaceMinVal : 1,
                tickSpaceMajVal : 10, 
                divID : "gaugeBox2", 
                gaugeUnits : "%"
			}
			gauges[1] = new drawGauge(opt);
			opt = {
				gaugeRadius : 100,
                minVal : 0,
                maxVal : 100,
                needleVal : Math.round(0),
                tickSpaceMinVal : 1,
                tickSpaceMajVal : 10, 
                divID : "gaugeBox3", 
                gaugeUnits : "%"
			}
			gauges[2] = new drawGauge(opt);
			opt = {
				gaugeRadius : 100,
                minVal : 0,
                maxVal : 100,
                needleVal : Math.round(0),
                tickSpaceMinVal : 1,
                tickSpaceMajVal : 10, 
                divID : "gaugeBox4", 
                gaugeUnits : "%"
			}
			gauges[3] = new drawGauge(opt);
			
			setInterval(function()
				{ 
				for (i = 0; i < ((medidoresId).length); i++) 
					{ 
					retornaUltimaLeitura(medidoresId[i], i);
					}
					atualizaAlertas();
				}, 3000);
		})