<?php


use migrations\migrations\MigrationManager;

require_once __DIR__ . '/../../config.php';
require_once ROOT_DIR . '/class/Mysql.php';
require_once ROOT_DIR . '/migrations/migrations/MigrationManager.php';


if (isset($argv)) // запуск из командной строки
{
    $migrationManager = new MigrationManager($PDO);
    $migrationManager->down();
} else
{
    header('Location: /404');
}