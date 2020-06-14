<?php
require_once("banco.php");
function retornaAlerta($id)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("SELECT id, textoDescricao, horario, idAnotacao, peso, idMedidor FROM Alerta WHERE id = ".$id);
	$statement->execute();            
	return($statement->fetchAll()[0]);   
	}
function retornaAlertasApos($numalerta)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("SELECT id, textoDescricao, horario, idAnotacao, peso, idMedidor FROM Alerta WHERE id > ".$numalerta." ORDER BY id ASC ");
	$statement->execute();            
	return($statement->fetchAll());   
	}
function excluiAlerta($id)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("DELETE FROM Alerta WHERE id =".$id);
	$statement->execute();            
	}
if (isset($_POST['funcao'])){
	switch ($_POST['funcao']) {
		case "retornaAlertas":
			$obtemalertas = retornaAlertasApos($_POST['parametro1']);
			$listAlertas = new stdClass();
			for ($x=0;$x < (count($obtemalertas));$x++)
				{
				$listAlertas->id[] = $obtemalertas[$x]['id'];
				$listAlertas->textoDescricao[] = $obtemalertas[$x]['textoDescricao'];
				$listAlertas->horario[] = $obtemalertas[$x]['horario'];
				$listAlertas->idAnotacao[] = $obtemalertas[$x]['idAnotacao'];
				$listAlertas->peso[] = $obtemalertas[$x]['peso'];
				$listAlertas->idMedidor[] = $obtemalertas[$x]['idMedidor'];
				}
			$leituraJSON = json_encode($listAlertas);
			echo $leituraJSON;
			break;
		default:
		   echo "Error";
		   break;
	}
}
?>