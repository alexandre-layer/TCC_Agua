<?php
require_once("banco.php");
function buscaMedidores()
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("SELECT * FROM Medidor ORDER BY id asc;");
	$statement->execute();
	return($statement->fetchAll());
	}
function buscaMedidor($idMedidor)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("SELECT * FROM Medidor WHERE id =".$idMedidor.";");
	$statement->execute();
	return($statement->fetchAll());
	}
function insereMedidor($nome,$topico,$descricao,$fator)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("INSERT INTO Medidor (nome, topico, descricao, fator) VALUES ('".$nome."','".$topico."','".$descricao."',".$fator.")");
	$statement->execute();
	}