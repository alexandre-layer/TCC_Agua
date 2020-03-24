<?php
require_once("banco.php");
function retornaConfig()
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("SELECT usuarioBroker, senhaBroker, enderecoBroker  FROM Configuracao WHERE id = 0");
	$statement->execute();            
	return($statement->fetchAll()[0]);   
	}
function manterConfig($usuario, $senha, $endereco)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("UPDATE Configuracao SET usuarioBroker ='".$usuario."', senhaBroker = '".$senha."', enderecoBroker = '".$endereco."' WHERE id = 0");
	$statement->execute();
	}
?>