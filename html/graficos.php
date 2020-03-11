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
	require_once('cntrl/medidorfcn.php')
?>
<center>
	<div class="corpo">
			
		<center>
			<?php
			$todosMedidores = buscaMedidores();
			$medidorAtual = $todosMedidores[0]['id'];
			if (isset($_GET['medidor'])) 
			{
			$medidorAtual=filter_var($_GET['medidor'], FILTER_SANITIZE_NUMBER_INT);
			}
			?>
			<script>
			idDoMedidor = <?php echo $medidorAtual; ?>
			</script>
				
				<div class="menuesquerda"><br><br> 
					<?php
					
					for ($i=0; $i < (count($todosMedidores)); $i++) {
					?>
					<a href="graficos.php?medidor=<?php echo $todosMedidores[$i]['id'] ?>">
					<?php echo $todosMedidores[$i]['nome'];?>
					</a><br><?php
					}?>	
				</div>
					<div id="grafico01" class="grafico">Gráfico - <?php echo $todosMedidores[$medidorAtual - 1]['nome'] ?>
						<br>
						<canvas id="line-chart1" ></canvas>
							
					</div>
					<br>
					
					<div id="grafico02" class="grafico">Gráfico - <?php echo $todosMedidores[$medidorAtual - 1]['nome'] ?>
						
						<br>
						<canvas id="line-chart2" ></canvas>
					</div>
					<br><?php
			?>
		</center>
	</div>
<center>	
<script src="js/chart.medidor.graficos.js"></script>	
</body>

</html>