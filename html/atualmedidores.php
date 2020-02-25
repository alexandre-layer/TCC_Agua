<?php
require_once("banco.php");
function buscaMedidores()
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("SELECT * FROM Medidor ORDER BY id asc;");
	$statement->execute();
	return($statement->fetchAll());
	}
function buscaRegistros($topico)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("SELECT * FROM Registro WHERE  topicoMedidor ='" . $topico ."'ORDER BY idRegistro desc limit 1;");
	$statement->execute();
	return($statement->fetchAll());
	}
		
function retornaRegistros()
	{
	$registros = array();
	$medidores = buscaMedidores();
	$tamanho = count($medidores);
	foreach ($medidores as $atual)
		{
		$registros[] = buscaRegistros($atual['topico'])[0];
		}
	return($registros);
	}

$registrosatuais = retornaRegistros();

for ($x = 0; $x <= 3; $x++) 
	{
	echo $registrosatuais[$x][topicoMedidor]."=";
	echo $registrosatuais[$x][valor]."<br>";
	} 
?>
</body>
</html>
