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
			$datafinal = date("Y-m-d h:i:s");
			$todosMedidores = buscaMedidores();
			$medidorAtual = $todosMedidores[0]['id'];
			if (isset($_GET['medidor'])) 
				{
				$medidorAtual=filter_var($_GET['medidor'], FILTER_SANITIZE_NUMBER_INT);
				$pagina = $_POST['pagina'];
				if (isset($_POST['excluir']))
					{
					excluiAnotacao($_POST['excluir']);
					?>
					<script>
					alert('Registro Excluido');
					window.location.href = "exibeanotacoes.php?<?php echo "medidor=".$medidorAtual."&pagina=".$pagina;?> ";
					</script>
					<?php
					}
				
				if (isset($_POST['exibir']))
					{
					$anotacao = retornaAnotacao($_POST['exibir']);
					$datainicial = $anotacao['horaInicio'];
					$datafinal = $anotacao['horaFim'];
					}
				}
			?>
			<script>
			idDoMedidor = <?php echo $medidorAtual; ?>;
			horarioInicial = <?php echo json_encode($datainicial); ?>;
			horarioFinal = <?php echo json_encode($datafinal); ?>;
			</script>
				
		<center>
			<div id="grafico01" class="grafico">Gráfico - <?php echo $todosMedidores[$medidorAtual - 1]['nome'] ?>
				<br><canvas id="line-chart1" ></canvas>
			</div>
			<div class="graficontrol">
			
				<b>Dados da anotação</b><br>
				Início: <?php echo $datainicial; ?><br>
				Fim: <?php echo $datafinal; ?><br>
				Nome do medidor: <?php echo $todosMedidores[$medidorAtual - 1]['nome'] ?>
				<br>
				<a href="exibeanotacoes.php?medidor=<?php echo $medidorAtual; ?>&pagina=<?php echo $pagina; ?>">Voltar</a>
				
				
			</div>
		</center>
	</div>
<script src="js/chart.medidor.historico.js"></script>	
</body>

</html>