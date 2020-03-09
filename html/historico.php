
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
		</div>
		<center>
			<div id="grafico01" class="grafico">
				Gráfico - medidor 01<br>
				<img src="img/graf1.jpg">
			</div>
			<div class="graficontrol">
				Exibição<br>
				De &nbsp <input type="date"><input type="time">&nbsp Até &nbsp <input type="date"><input type="time">
				<button type="submit" name="atualgrafico" value="atgraf">Atualizar gráfico</button>
				<br><br>
				<button type="submit" name="admarc" value="admarc">Adicionar marcação...</button>
				<button type="submit" name="amarc" value="marc">Exibir marcações</button>
			</div>
			
		</center>
	</div>
<center>	
</body>

</html>