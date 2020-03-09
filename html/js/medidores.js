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

$(document).ready(function(){
$.post("cntrl/medidor.php",
    {
       funcao:"listaMedidores",
    },
    function(data,status){
      resposta = JSON.parse(data)
	  medidoresId = resposta.id;
	});
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
					
			}, 3000);
		})
		
