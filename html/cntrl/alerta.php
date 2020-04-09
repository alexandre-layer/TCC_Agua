<?php
require_once("banco.php");

function retornaAlerta($id)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("SELECT idAlerta, textoDescricao, horario, idAnotacao FROM Alerta WHERE idAlerta = ".$id);
	$statement->execute();            
	return($statement->fetchAll()[0]);   
	}
function retornaAlertas($numalertas)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("SELECT idAlerta, textoDescricao, horario, idAnotacao FROM Alerta ORDER BY idAlerta DESC LIMIT ".$numalertas);
	$statement->execute();            
	return($statement->fetchAll());   
	}
function excluiAlerta($id)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("DELETE FROM Alerta WHERE idAlerta =".$id);
	$statement->execute();            
	}
?>