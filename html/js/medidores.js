var xmlhttp = new XMLHttpRequest();
var valores = [];
xmlhttp.onreadystatechange = function() {
  if (this.readyState == 4 && this.status == 200) {
    myObj = JSON.parse(this.responseText);
    valores = myObj.valor;
  }
};

var gauges  = []
		document.addEventListener("DOMContentLoaded", function(event) {
			var opt = {
				gaugeRadius : 100,
                minVal : 0,
                maxVal : 100,
                needleVal : Math.round(30),
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
                needleVal : Math.round(60),
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
                needleVal : Math.round(20),
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
                needleVal : Math.round(40),
                tickSpaceMinVal : 1,
                tickSpaceMajVal : 10, 
                divID : "gaugeBox4", 
                gaugeUnits : "%"
			}
			gauges[3] = new drawGauge(opt);
			
			setInterval(function()
				{ 
					$.getJSON("atualmedidoresjson.php", function(result){
					$.each(result, function(i, field){
					xmlhttp.open("GET", "atualmedidoresjson.php", true);
					xmlhttp.send();
					//window.alert(valores);
						});
					});
				var i;
				for (i = 0; i < 4; i++) 
					{ 
					gauges[i].updateGauge(valores[i]);
					}
				}, 3000);
		})
		
