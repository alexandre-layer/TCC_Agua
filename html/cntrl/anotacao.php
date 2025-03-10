<?php
require_once("banco.php");
function retornaTiposAnotacao()
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("SELECT idTipo, nome  FROM tipoAnotacao");
	$statement->execute();            
	return($statement->fetchAll());   
	}

function retornaAnotacao($id)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("SELECT id, horaInicio, horaFim, tipoAnotacao FROM Anotacao WHERE id = ".$id);
	$statement->execute();            
	return($statement->fetchAll()[0]);   
	}
function retornaAnotacoes($pagina, $medidorAnotacao)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("SELECT id, horaInicio, horaFim, tipoAnotacao FROM Anotacao WHERE idMedidor = ".$medidorAnotacao." ORDER BY id ASC LIMIT ".(($pagina-1)*15).", 15");
	$statement->execute();            
	return($statement->fetchAll());   
	}

function insereAnotacao($hinicio,$hfim, $idTipoAnotacao, $idMedidor)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("INSERT INTO Anotacao (horaInicio, horaFim, tipoAnotacao, idMedidor) VALUES ('".$hinicio."' , '".$hfim."', ".$idTipoAnotacao.", ".$idMedidor.")");
	$statement->execute();            
	}
function excluiAnotacao($id)
	{
	$pdo = Banco::obterConexao();
	$statement = $pdo->prepare("DELETE FROM Anotacao WHERE id =".$id);
	$statement->execute();            
	}

?>