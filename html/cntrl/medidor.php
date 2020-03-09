<?php
require_once("medidorfcn.php");
switch ($_POST['funcao']) {
    case "infoMedidor":
        $obtermedidor = buscaMedidor($_POST['parametro1']);
		$medidor = new stdClass();
		$medidor->id = $obtermedidor[0]['id'];
		$medidor->nome = $obtermedidor[0]['nome'];
		$medidor->topico = $obtermedidor[0]['topico'];
		$medidor->descricao = $obtermedidor[0]['descricao'];
		$medidorJSON = json_encode($medidor);
		echo $medidorJSON;
		break;
	case "listaMedidores":
        $medidores = buscaMedidores();
		$medidor = new stdClass(); 
		for ($x=0;$x < (count($medidores));$x++)
			{
			$medidor->id[] = $medidores[$x]['id'];
			$medidor->nome[] = $medidores[$x]['nome'];
			$medidor->topico[] = $medidores[$x]['topico'];
			$medidoresJSON = json_encode($medidor);
			}
		echo $medidoresJSON;
        break;
    case "adicionarMedidor":
		$nome = $_POST['parametro1'];
		$topico = $_POST['parametro2'];
		$descricao = $_POST['parametro3'];
		$fator = $_POST['parametro4'];
		insereMedidor($nome,$topico,$descricao,$fator);
        echo "Sucesso";
        break;
	default:
       echo "Error";
	   break;
}
?>