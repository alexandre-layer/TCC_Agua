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
		if (isset($_GET['medidor'])) {
			$medidorAtual=filter_var($_GET['medidor'], FILTER_SANITIZE_NUMBER_INT);
			if(isset($_POST['inserir'])){
				insereMedidor($_POST['nome'],$_POST['topico'],$_POST['descricao'],$_POST['fator']);
				}
			}
		?>
		<center>
			<div class="config">
				Configurações do medidor<br><br><br>
				<form action="medidores.php?medidor=<?php echo $medidorAtual; ?>" method="POST"> 
				<table>
					<tr>
						<td><p align="left">Nome</p></td>
						<td><input type="text" name="nome" value="Nome"></td>
					</tr>
					<tr>
						<td><p align="left">Tópico</p></td>
						<td><input type="text" name="topico" value="/"></td>
					</tr>
					<tr>
						<td><p align="left">Descrição</p></td>
						<td><input type="text" name="descricao" value="Descrição"></td>
					</tr>
					<tr>
						<td><p align="left">Fator</p></td>
						<td><input type="text" name="fator" value="1.00"></td>
					</tr>
					<tr>
						<td><p align="left">ID</p></td>
						<td><input type="text" name="idmedidor" value="<?php echo $medidorAtual;?>" disabled></td>
					</tr>
				</table>
				<br><br>
				<input type="submit" value="Adicionar" name="inserir">
				</form>
			</div>
		</center>
	</div>
<center>	
</body>
</html>