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
	require_once('cntrl/config.php');
	if(isset($_POST['salvar']))
				{
				manterConfig($_POST['usuario'],$_POST['senha'], $_POST['servidor']);
				echo "<script>alert('Salvo com sucesso');</script>";
				}
	$config = retornaConfig();
?>
<center>
	<div class="corpo">
		<center>
			<div class="config">
				Configurações do broker<br><br><br>
				<form action="configura.php" method="POST">
				<table>
					<tr>
						<td><p align="left">Usuário</p></td>
						<td><input type="text" name="usuario" value="<?php echo $config['usuarioBroker']; ?>"></td>
					</tr>
					<tr>
						<td><p align="left">Senha</p></td>
						<td><input type="password" name="senha" id="senha" value="<?php echo $config['senhaBroker']; ?>"> <img id="exibe" src="img/exibe.gif"></td>
					</tr>
					<tr>
						<td><p align="left">Hostname/IP</p></td>
						<td><input type="text" name="servidor" value="<?php echo $config['enderecoBroker']; ?>"></td>
					</tr>
				</table>
				<br><br>
				<input type="submit" value="Salvar" name="salvar">
				</form>
			</div>
		</center>
	</div>
<center>	
<script>
$('#exibe').click(function(){
	if ($("#senha").attr('type') == "password") $("#senha").attr('type','text');
	else $("#senha").attr('type','password');
});
</script>
</body>

</html>