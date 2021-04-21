<?php


namespace migrations\migrations;

use PDO;

require_once __DIR__ . '/../../config.php';
require_once ROOT_DIR . '/migrations/migrations/Migration.php';

class ArticlesMigration extends Migration
{
    private $table;

    public function __construct(PDO $PDO)
    {
        parent::__construct($PDO);
        $this->table = 'articles';
    }

    public function up()
    {
        $table = $this->table;
        $column = ['id INT AUTO_INCREMENT PRIMARY KEY', 'title VARCHAR(200)', 'text TEXT', 'date INT(11)',
            'autor VARCHAR(50)', 'file VARCHAR(50)', 'visit INT(11)'];
        $column = implode(', ', $column);
        $this->PDO->exec("CREATE TABLE IF NOT EXISTS $table ($column) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    }

    public function down()
    {
        $table = $this->table;
        $this->PDO->exec("DROP TABLE IF EXISTS $table");
    }

}