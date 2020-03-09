<?php
require_once("banco.php");
function buscaUltimoRegistro($idMedidor)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("SELECT horario,valor FROM Registro WHERE horario = (select max(horario) FROM Registro WHERE idMedidor=".$idMedidor.")");
	$statement->execute();
	return($statement->fetchAll());
	}
function retornaRegistros($idMedidor, $inicio, $fim)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("SELECT horario,valor FROM Registro WHERE idMedidor =".$idMedidor." AND (horario BETWEEN '".$inicio."' AND '".$fim."')");
	$statement->execute();
	return($statement->fetchAll());
	}
function retornaRegistrosDia($idMedidor)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("SELECT horario,valor FROM Registro WHERE idMedidor =".$idMedidor." AND horario >= NOW() - INTERVAL 1 DAY");
	$statement->execute();
	return($statement->fetchAll());
	}	
switch ($_POST['funcao']) {
    case "ultimaLeitura":
		$obtemregistro = buscaUltimoRegistro($_POST['parametro1']);
		$leitura = new stdClass();
		$leitura->horario = $obtemregistro[0]['horario'];
		$leitura->valor = $obtemregistro[0]['valor'];
		$leituraJSON = json_encode($leitura);
		echo $leituraJSON;
		break;
	case "leituras":
		$obtemregistro = retornaRegistros($_POST['parametro1'],$_POST['parametro2'],$_POST['parametro3']);
		$leitura = new stdClass();
		for ($x=0;$x < (count($obtemregistro));$x++)
			{
			$leitura->horario[] = $obtemregistro[$x]['horario'];
			$leitura->valor[] = $obtemregistro[$x]['valor'];
			}
		$leituraJSON = json_encode($leitura);
		echo $leituraJSON;
		break;
	case "leituraDia":
		$obtemregistro = retornaRegistrosDia($_POST['parametro1']);
		$leitura = new stdClass();
		for ($x=0;$x < (count($obtemregistro));$x++)
			{
			$leitura->horario[] = $obtemregistro[$x]['horario'];
			$leitura->valor[] = $obtemregistro[$x]['valor'];
			}
		$leituraJSON = json_encode($leitura);
		echo $leituraJSON;
		break;
		
	default:
       echo "Error";
	   break;
}
?>