<?php

use migrations\migrations\AdminMigration;
use migrations\migrations\AdviceMigration;
use migrations\migrations\ArticlesMigration;
use migrations\migrations\CommentMigration;
use migrations\migrations\CompanyMigration;
use migrations\migrations\MigrationManager;
use migrations\migrations\ReviewHrMigration;
use migrations\migrations\ReviewMigration;
use migrations\migrations\SettingsMigration;

require_once __DIR__ .  '/../../config.php';
require_once ROOT_DIR . '/class/Mysql.php';
require_once ROOT_DIR . '/migrations/migrations/MigrationManager.php';

if (isset($argv)) // запуск из командной строки
{
    $migrationManager = new MigrationManager($PDO);
    $migrationManager->up();
} else {
    header('Location: /404');
}