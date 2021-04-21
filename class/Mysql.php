<?php

include_once "credentials.php";
$host = $dbCredentials['host'];
//$host = 'localhost';
$user = $dbCredentials['user'];
//$user = 'ca88265373_rev';
$pass = $dbCredentials['pass'];
//$pass = "%e43DgcR";
$db   = $dbCredentials['db'];
//$db   = 'ca88265373_rev';
$charset = $dbCredentials['charset'];
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
