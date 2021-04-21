<?php


use migrations\seeders\DumpSeeder;

require_once __DIR__ . '/../../config.php';
require_once ROOT_DIR . '/class/Mysql.php';
require_once ROOT_DIR . '/migrations/seeders/DumpSeeder.php';


if (isset($argv)) // запуск из командной строки
{
    $seeders = [
        DumpSeeder::class
    ];

    foreach ($seeders as $seeder) {
        $seederObj = new $seeder($PDO);
        $seederObj->run();
        echo $seeder . '::run() - выполнена' . "\n";
    }
} else {
    header('HTTP/1.0 404 not found');
    exit();
}