<?php
$host = '127.0.0.1';
//$host = 'localhost';
$user = 'root';
//$user = 'ca88265373_rev';
$pass = "12345";
//$pass = "%e43DgcR";
$db   = 'rating_moscow';
//$db   = 'ca88265373_rev';
$charset = 'utf8';
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
