<?php


namespace migrations\migrations;

use PDO;

require_once __DIR__ . '/../../config.php';
require_once ROOT_DIR . '/migrations/migrations/Migration.php';


class ReviewMigration extends Migration
{
    public function __construct(PDO $PDO)
    {
        parent::__construct($PDO, 'review');
    }

    public function up()
    {
        $table = $this->table;
        $column = ['id INT AUTO_INCREMENT PRIMARY KEY', 'fio VARCHAR(100)', 'email VARCHAR(100)', 'rev INT(1)', 'service VARCHAR(50)',
            'text TEXT', 'type INT(11)', 'moderation INT(11)', 'id_com INT(11)', 'data VARCHAR(15)', 'pos INT(1)', 'pars_id VARCHAR(25) NULL',
            'view VARCHAR(20)'];
        $column = implode(', ', $column);
        $this->PDO->exec("CREATE TABLE IF NOT EXISTS $table ($column) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    }
}