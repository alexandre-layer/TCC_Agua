<?php
require_once("banco.php");

function retornaAlerta($id)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("SELECT id, textoDescricao, horario, id FROM Alerta WHERE id = ".$id);
	$statement->execute();            
	return($statement->fetchAll()[0]);   
	}
function retornaAlertas($numalertas)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("SELECT id, textoDescricao, horario, id FROM Alerta ORDER BY id DESC LIMIT ".$numalertas);
	$statement->execute();            
	return($statement->fetchAll());   
	}
function excluiAlerta($id)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("DELETE FROM Alerta WHERE id =".$id);
	$statement->execute();            
	}
?>