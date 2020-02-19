
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>SIMCONA</title>
	<link rel="stylesheet" type="text/css" href="reset.css">
	<link rel="stylesheet" type="text/css" href="css.css">
	<script src="js/jquery-3.4.1.min.js"></script>
	<script src="js/Chart.js"></script>
	
</head>
<body>
<?php
	require_once('supnavbar.html');
?>
<center>
	<div class="corpo">
		<center>
			<?php
			for ($i = 1; $i <= 4; $i++) {
				?>	<div class="grafesquerda"><br><br> MEDIDOR 
				<?php
				echo  $i;
				?>
					<br>Consumo acumulado<BR>			
					</div>
					
					<div id="grafico01" class="grafico">Gráfico - Medidor 
						<?php
						echo $i;
						?>
						<br>
							<img src="img/graf1.jpg">
					</div>
					<br>
					<div class="grafesquerda"><br><br> MEDIDOR 
				<?php
				echo  $i;
				?>
					<br>Vazão atual<BR>			
					</div>
					
					<div id="grafico01" class="grafico">Gráfico - Medidor 
						<?php
						echo $i;
						?>
						<br>
							<img src="img/graf1.jpg">
					</div>
					<br><?php
			}?>
		</center>
	</div>
<center>	
</body>

</html>