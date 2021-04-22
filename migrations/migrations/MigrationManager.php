<?php


namespace migrations\migrations;

require_once ROOT_DIR . '/migrations/migrations/AdminMigration.php';
require_once ROOT_DIR . '/migrations/migrations/AdviceMigration.php';
require_once ROOT_DIR . '/migrations/migrations/ArticlesMigration.php';
require_once ROOT_DIR . '/migrations/migrations/CommentMigration.php';
require_once ROOT_DIR . '/migrations/migrations/CompanyMigration.php';
require_once ROOT_DIR . '/migrations/migrations/ReviewMigration.php';
require_once ROOT_DIR . '/migrations/migrations/ReviewHrMigration.php';
require_once ROOT_DIR . '/migrations/migrations/SettingsMigration.php';

use PDO;

class MigrationManager
{
    private $PDO;
    private $migrations = [
        AdminMigration::class,
        AdviceMigration::class,
        ArticlesMigration::class,
        CommentMigration::class,
        CompanyMigration::class,
        ReviewMigration::class,
        ReviewHrMigration::class,
        SettingsMigration::class
    ];

    public function __construct(PDO $PDO) {
        $this->PDO = $PDO;
    }

    public function up()
    {
        foreach ($this->migrations as $migration) {
            $migrationObj =  new $migration($this->PDO);
            $migrationObj->up();
            echo $migration . '::up() - выполнена' . "\n";
        }
    }

    public function down()
    {
        $migrations = array_reverse($this->migrations);
        foreach ($migrations as $migration)
        {
            $migrationObj = new $migration($this->PDO);
            $migrationObj->down();
            echo $migration . '::down() - выполнена' . "\n";
        }
    }
}