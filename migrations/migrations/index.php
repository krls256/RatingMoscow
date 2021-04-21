<?php

use migrations\migrations\AdminMigration;
use migrations\migrations\AdviceMigration;
use migrations\migrations\ArticlesMigration;
use migrations\migrations\CommentMigration;
use migrations\migrations\CompanyMigration;
use migrations\migrations\ReviewHrMigration;
use migrations\migrations\ReviewMigration;
use migrations\migrations\SettingsMigration;

require_once __DIR__ .  '/../../config.php';
require_once ROOT_DIR . '/class/Mysql.php';
require_once ROOT_DIR . '/migrations/migrations/AdminMigration.php';
require_once ROOT_DIR . '/migrations/migrations/AdviceMigration.php';
require_once ROOT_DIR . '/migrations/migrations/ArticlesMigration.php';
require_once ROOT_DIR . '/migrations/migrations/CommentMigration.php';
require_once ROOT_DIR . '/migrations/migrations/CompanyMigration.php';
require_once ROOT_DIR . '/migrations/migrations/ReviewMigration.php';
require_once ROOT_DIR . '/migrations/migrations/ReviewHrMigration.php';
require_once ROOT_DIR . '/migrations/migrations/SettingsMigration.php';


if (isset($argv)) // запуск из командной строки
{
    $migrations = [
        AdminMigration::class,
        AdviceMigration::class,
        ArticlesMigration::class,
        CommentMigration::class,
        CompanyMigration::class,
        ReviewMigration::class,
        ReviewHrMigration::class,
        SettingsMigration::class
    ];
    foreach ($migrations as $migration) {
        $migrationObj =  new $migration($PDO);
        $migrationObj->up();
        echo $migration . '::up() - выполнена' . "\n";
    }
} else {
    header('HTTP/1.0 404 not found');
    exit();
}