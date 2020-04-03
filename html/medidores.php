
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
?>
<center>
<div class="corpo">
		<?php
		$todosMedidores = buscaMedidores();
		$medidorAtual = $todosMedidores[0]['id'];
		$indexMedidorAtual = 0;
		if (isset($_GET['medidor'])) 
			{
			$medidorAtual=filter_var($_GET['medidor'], FILTER_SANITIZE_NUMBER_INT);
			if(isset($_POST['salvar']))
				{
				manterMedidor($medidorAtual, $_POST['nome'],$_POST['topico'],$_POST['descricao'],$_POST['fator']);
				}
			if(isset($_POST['inserir']))
				{
				insereMedidor($_POST['nome'],$_POST['topico'],$_POST['descricao'],$_POST['fator']);
				}
			if(isset($_POST['excluir']))
				{
				excluiMedidor($medidorAtual);
				}
			}
			$todosMedidores = buscaMedidores();
		?>
	<div class="menuesquerda">
			<?php
				for ($i=0; $i < (count($todosMedidores)); $i++) {
					?>
					<a href="medidores.php?medidor=<?php echo $todosMedidores[$i]['id'] ?>">
					<?php echo $todosMedidores[$i]['nome'];
					 if ($todosMedidores[$i]['id'] == $medidorAtual) $indexMedidorAtual = $i;?>
					</a><br><?php
					}?>	
		<BR><br>			
		<a href="inseremedidor.php"><p align="right">Adicionar...</p></a><br><br><br>
	</div>
	<center>
		<div class="config">
			Configurações do medidor<br><br><br>
			<form action="medidores.php?medidor=<?php echo $medidorAtual; ?>" method="POST"> 
			<table>
				<tr>
					<td><p align="left">Nome</p></td>
					<td><input type="text" name="nome" value="<?php echo $todosMedidores[$indexMedidorAtual]['nome'];?>"></td>
				</tr>
				<tr>
					<td><p align="left">Tópico</p></td>
					<td><input type="text" name="topico" value="<?php echo $todosMedidores[$indexMedidorAtual]['topico'];?>"></td>
				</tr>
				<tr>
					<td><p align="left">Descrição</p></td>
					<td><input type="text" name="descricao" value="<?php echo $todosMedidores[$indexMedidorAtual]['descricao'];?>"></td>
				</tr>
			
				<tr>
					<td><p align="left">Fator</p></td>
					<td><input type="text" name="fator" value="<?php echo $todosMedidores[$indexMedidorAtual]['fator'];?>"></td>
				</tr>
				<tr>
					<td><p align="left">ID</p></td>
					<td><input type="text" name="idmedidor" value="<?php echo $medidorAtual;?>" disabled></td>
				</tr>
			</table>
			<br><br>
			<input type="submit" value="Excluir Medidor" name="excluir"><input type="submit" value="Salvar" name="salvar">
			</form>
		</div>
	</center>
</div>
<center>	
</body>
</html>