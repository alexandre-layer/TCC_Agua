<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>SIMCONA</title>
	<link rel="stylesheet" type="text/css" href="reset.css">
	<link rel="stylesheet" type="text/css" href="css.css">
	<script src="js/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="js/d3.v4.min.js" charset="utf-8"></script>	
	<script src="js/d3gauge.js"></script>
	<script type="text/javascript" src="js/medidores.js"></script>
</head>
<body>
<?php
	require_once('supnavbar.html');
?>
<center>
	<div class="corpo">
		<div class="alertas">
			Alertas
			<select class="listalertas" id="listAlertas" name="alertas" size="20" multiple style="max-width:300px;">
			</select>
		</div>
		<center>
			<div class="medidores">
					<div id="gaugeBox1" class="medidor">Medidor 1</div>
					<div id="gaugeBox2" class="medidor">Medidor 2</div>
					<div id="gaugeBox3" class="medidor">Medidor 3</div>
					<div id="gaugeBox4" class="medidor">Medidor 4</div>
			</div>
		</center>
	</div>
<center>	
</body>
</html>