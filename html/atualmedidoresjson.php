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
		
function retornaRegistros($medidores)
	{
	$registros = array();
	//$medidores = buscaMedidores();
	$tamanho = count($medidores);
	foreach ($medidores as $atual)
		{
		$registros[] = buscaRegistros($atual['topico'])[0];
		}
	return($registros);
	}
$medidores = buscaMedidores();
$registrosatuais = retornaRegistros($medidores);
for ($x=0;$x < (count($medidores));$x++)
	{
	$medidor->nome[] = $medidores[$x]['nome'];
	$medidor->topico[] = $registrosatuais[$x]['topicoMedidor'];
	$medidor->valor[] = $registrosatuais[$x]['valor'];
	$medidoresJSON = json_encode($medidor);
	}
echo $medidoresJSON;
?>
</body>
</html>
