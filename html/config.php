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
			<div class="config">
				Configurações do broker<br><br><br>
				<table>
					<tr>
						<td><p align="left">Usuário</p></td>
						<td><input type="text" name="usuario" value="Usuário"></td>
					</tr>
					<tr>
						<td><p align="left">Senha</p></td>
						<td><input type="password" name="senha" value="Senha"></td>
					</tr>
					<tr>
						<td><p align="left">Hostname/IP</p></td>
						<td><input type="text" name="servidor" value="mqtt.br"></td>
					</tr>
				</table>
				<br><br>
				<button >Salvar</button>
			</div>
		</center>
	</div>
<center>	
</body>

</html>