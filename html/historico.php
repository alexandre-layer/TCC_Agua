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
		<?php
			$datainicial = "2020-01-01 23:13:24";
			$datafinal = "2020-03-09 23:13:24";
			$todosMedidores = buscaMedidores();
			$medidorAtual = $todosMedidores[0]['id'];
			if (isset($_GET['medidor'])) 
				{
				$medidorAtual=filter_var($_GET['medidor'], FILTER_SANITIZE_NUMBER_INT);
				if (isset($_POST['dataini']))
					{
					$datainicial = $_POST["dataini"]." ".$_POST["horaini"].":00";
					$datafinal = $_POST["datafim"]." ".$_POST["horafim"].":00";
					}
				}
			?>
			<script>
			idDoMedidor = <?php echo $medidorAtual; ?>;
			horarioInicial = <?php echo json_encode($datainicial); ?>;
			horarioFinal = <?php echo json_encode($datafinal); ?>;
			</script>
				<div class="menuesquerda"><br><br> 
					<?php
					
					for ($i=0; $i < (count($todosMedidores)); $i++) {
					?>
					<a href="historico.php?medidor=<?php echo $todosMedidores[$i]['id'] ?>">
					<?php echo $todosMedidores[$i]['nome'];?>
					</a><br><?php
					}?>	
				</div>
		<center>
			<div id="grafico01" class="grafico">Gráfico - <?php echo $todosMedidores[$medidorAtual - 1]['nome'] ?>
				<br><canvas id="line-chart1" ></canvas>
			</div>
			<div class="graficontrol">
				Exibição<br>
				De&nbsp <form method="post" action="historico.php?medidor=<?php echo $medidorAtual ?>">
				<input type="date" name="dataini">
				<input type="time" name="horaini">&nbsp Até &nbsp 
				<input type="date" name="datafim">
				<input type="time" name="horafim">
				<input type="submit" name="submit" value="Exibir">  
				<br><br>
				<button type="submit" name="admarc" value="admarc">Adicionar marcação...</button>
				<button type="submit" name="amarc" value="marc">Exibir marcações</button>
				</form>
			</div>
		</center>
	</div>
<script src="js/chart.medidor.historico.js"></script>	
</body>

</html>