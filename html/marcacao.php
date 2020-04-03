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
	require_once('cntrl/medidorfcn.php');
	require_once('cntrl/anotacao.php');
?>
<center>
	<div class="corpo">
		<?php
			$datainicial = "2010-01-01 00:00:00";
			$datafinal = "2020-03-30 23:59:59";
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
					if (isset($_POST['adanotacao']))
					{
						insereAnotacao($datainicial,$datafinal, $_POST['tipo'], $_GET['medidor']);
						echo "<script>alert('Anotação Adicionada com sucesso'); window.location.href = 'historico.php';</script>";
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
				Anotação<br>
				<form method="post" action="marcacao.php?medidor=<?php echo $medidorAtual ?>">
					De &nbsp <input type="date" name="dataini" value="<?php echo explode(" ", $datainicial)[0]; ?>">
					<input type="time" name="horaini" value="<?php echo substr($datainicial,11,5); ?>">&nbsp Até &nbsp 
					<input type="date" name="datafim" value="<?php echo explode(" ", $datafinal)[0]; ?>">
					<input type="time" name="horafim" value="<?php echo substr($datafinal,11,5); ?>">
					<input type="submit" name="submit" value="Exibir">  
					<br><br>
				</form>
				
				<form method="post" action="marcacao.php?medidor=<?php echo $medidorAtual ?>">
					<input type="hidden" name="dataini" value="<?php echo explode(" ", $datainicial)[0]; ?>">
					<input type="hidden" name="horaini" value="<?php echo substr($datainicial,11,5); ?>">
					<input type="hidden" name="datafim" value="<?php echo explode(" ", $datafinal)[0]; ?>">
					<input type="hidden" name="horafim" value="<?php echo substr($datafinal,11,5); ?>">
					
					Tipo
					<select id="tipoAnotacao" name="tipo">
						<?php
						$tiposdeanotacao = retornaTiposAnotacao();
						for ($i=0; $i < (count($tiposdeanotacao)); $i++) {
						?><option value="<?php echo $tiposdeanotacao[$i]['idTipo']; ?>"><?php echo $tiposdeanotacao[$i]['nome']; ?></option>
						<?php 
						}
						?>						
					</select>
					<button type="submit" name="adanotacao" value="admarc">Adiciona anotação</button>
				</form>
			</div>
		</center>
	</div>
<script src="js/chart.medidor.historico.js"></script>	
</body>

</html>