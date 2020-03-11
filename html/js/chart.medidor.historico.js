var valorAcumulado =[];
function retornaPost() {
	
	$.post("cntrl/registro.php", {
		parametro1:idDoMedidor,
		parametro2: horarioInicial,
		parametro3: horarioFinal,
		funcao:"leituras"
		})
	.done(function(data) {
		var retorno = JSON.parse(data);
		valorAcumulado[0] = retorno.valor[0];
		for (var i = 1; i < (((retorno.valor).length)); i++) {
			valorAcumulado[i] = (parseInt(retorno.valor[i])+parseInt(valorAcumulado[i-1]));
			}
		new Chart(document.getElementById("line-chart1"), {
		  type: 'line',
		  data: {
			labels: retorno.horario,
			datasets: [{ 
				data: retorno.valor,
				label: "Consumo",
				borderColor: "#3e95cd",
				fill: false
			  }, 
			]
		  },
		  options: {
			title: {
			  display: true,
			  text: 'Consumo'
			}
		  }
		});		
		new Chart(document.getElementById("line-chart2"), {
		  type: 'line',
		  data: {
			labels: retorno.horario,
			datasets: [{ 
				data: valorAcumulado,
				label: "Consumo Acumulado",
				borderColor: "#3e95cd",
				fill: false
			  }, 
			]
		  },
		  options: {
			title: {
			  display: true,
			  text: 'Consumo'
			}
		  }
		});	
		
		
		});
}


$(document).ready(function(){
	
	retornaPost();
});
