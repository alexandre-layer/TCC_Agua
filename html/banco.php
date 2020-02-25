<?php
abstract class Banco
{
  public static function obterConexao()
  {
    $pdo = new PDO('mysql:host=localhost;dbname=simcona;charset=utf8mb4', 'aguasql', 'pass1368');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  }
}

