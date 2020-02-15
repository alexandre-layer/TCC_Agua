<?php
	$logado = false;
	session_start();
	if( isset( $_SESSION["usuario"] ) ){
		$logado = true;
	}
?>
Login session