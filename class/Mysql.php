<?php

include_once "credentials.php";
$host = $dbCredentials['host'];
$user = $dbCredentials['user'];
$pass = $dbCredentials['pass'];
$db   = $dbCredentials['db'];
$charset = $dbCredentials['charset'];
//$host = 'localhost';
//$user = 'ca88265373_rev';
//$pass = "%e43DgcR";
//$db   = 'ca88265373_rev';
//$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $PDO = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    die('Подключение не удалось: ' . $e->getMessage());
}
 ?>
