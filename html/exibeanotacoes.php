<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>SIMCONA</title>
	<link rel="stylesheet" type="text/css" href="reset.css">
	<link rel="stylesheet" type="text/css" href="css.css">
	<script src="js/jquery-3.4.1.min.js"></script>
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
			$paginaatual = 1;
			$todosMedidores = buscaMedidores();
			$medidorAtual = $todosMedidores[0]['id'];
			$tiposdeanotacao = retornaTiposAnotacao();
			if (isset($_GET['medidor'])) 
				{
				$medidorAtual=filter_var($_GET['medidor'], FILTER_SANITIZE_NUMBER_INT);
				if (isset($_GET['pagina']))
					{
					$paginaatual = filter_var($_GET['pagina'], FILTER_SANITIZE_NUMBER_INT);
					}
				}
			?>
			<script>
			</script>
				<div class="menuesquerda"><br><br> 
					<?php
					
					for ($i=0; $i < (count($todosMedidores)); $i++) {
					?>
					<a href="exibeanotacoes.php?medidor=<?php echo $todosMedidores[$i]['id']; ?>">
					<?php echo $todosMedidores[$i]['nome'];?>
					</a><br><?php
					}?>	
				</div>
		<center>
			<div class="anotacoes"><?php echo $todosMedidores[$medidorAtual - 1]['nome']; ?>
				<table class="anotacoes">
					<tr >
						<th class="anotacao">ID</td>
						<th class="anotacao">Inicio</td>
						<th class="anotacao">Fim</td>
						<th class="anotacao">Tipo</td>
					</tr>
				<?php
				$anotacoes = retornaAnotacoes($paginaatual, $medidorAtual);
				foreach ($anotacoes as &$anotacao) 
					{
					?>
					<tr>
						<td><?php echo $anotacao['idAnotacao']; ?></td>
						<td class="horario"><?php echo $anotacao['horaInicio']; ?></td>
						<td class="horario"><?php echo $anotacao['horaFim']; ?></td>
						<td class="tipoanotacao"><?php echo $tiposdeanotacao[($anotacao['tipoAnotacao'])]['nome']; ?></td>
					</tr>
					<?php
					}
				?>
				</table>
				<center>
				<br><br>
				<?php 
				if ($paginaatual>1) echo "<a href='exibeanotacoes.php?medidor=".$medidorAtual."&pagina=".($paginaatual - 1)."'><< </a>"; 
				echo "Pagina ".($paginaatual);
				if (sizeof($anotacoes) > 14) echo "<a href='exibeanotacoes.php?medidor=".$medidorAtual."&pagina=".($paginaatual + 1)."'>>></a>"; 
				?>
				</center>
			</div>
			
		</center>
	</div>
</body>

</html>