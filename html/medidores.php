
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
		<div class="menuesquerda">
			
			<?php
			for ($i = 1; $i <= 4; $i++) {
			?>
			<a href="#">MEDIDOR
			<?php echo $i;?>
			</a><br><?php
			}?>
			
			<BR><br>			
			<a href="#"><p align="right">Adicionar...</p></a><br><br><br>
		</div>
		<center>
			<div class="config">
				Configurações do medidor<br><br><br>
				<table>
					<tr>
						<td><p align="left">Nome</p></td>
						<td><input type="text" name="nome" value="Nome"></td>
					</tr>
					<tr>
						<td><p align="left">Tópico</p></td>
						<td><input type="text" name="topico" value="Topico"></td>
					</tr>
					<tr>
						<td><p align="left">Descrição</p></td>
						<td><input type="text" name="descricao" value="Descricao"></td>
					</tr>
				
					<tr>
						<td><p align="left">Fator</p></td>
						<td><input type="text" name="fator" value="1.0"></td>
					</tr>
					<tr>
						<td><p align="left">ID</p></td>
						<td><input type="text" name="idmedidor" value="0" disabled></td>
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