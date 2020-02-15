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
                needleVal : Math.round(70),
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
			
		})