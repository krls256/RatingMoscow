<?php


namespace migrations\migrations;

use PDO;

require_once __DIR__ . '/../../config.php';
require_once ROOT_DIR . '/migrations/migrations/Migration.php';

class ReviewHrMigration extends Migration
{
    public function __construct(PDO $PDO)
    {
        parent::__construct($PDO, 'review_hr');
    }

    public function up()
    {
        $table = $this->table;
        $column = ['id INT AUTO_INCREMENT PRIMARY KEY', 'position VARCHAR(150)', 'rev INT(1)', 'pars_id BIGINT(20) NULL', 'text TEXT',
            'data BIGINT(20)', 'id_com BIGINT(20) NULL'];
        $column = implode(', ', $column);
        $this->PDO->exec("CREATE TABLE IF NOT EXISTS $table ($column) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    }
}