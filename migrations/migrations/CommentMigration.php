<?php


namespace migrations\migrations;

use PDO;

require_once __DIR__ . '/../../config.php';
require_once ROOT_DIR . '/migrations/migrations/Migration.php';


class CommentMigration extends Migration
{
    private $table;

    public function __construct(PDO $PDO)
    {
        parent::__construct($PDO);
        $this->table = 'comment';
    }

    public function up()
    {
        $table = $this->table;
        $column = ['id INT AUTO_INCREMENT PRIMARY KEY', 'fio VARCHAR(100)', 'text TEXT', 'review INT(11)',
            'moderation INT(1)', 'data INT(11)', 'type VARCHAR(30) NULL'];
        $column = implode(', ', $column);
        $this->PDO->exec("CREATE TABLE IF NOT EXISTS $table ($column) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    }

    public function down()
    {
        $table = $this->table;
        $this->PDO->exec("DROP TABLE IF EXISTS $table");
    }

}