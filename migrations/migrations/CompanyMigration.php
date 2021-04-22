<?php

namespace migrations\migrations;

use PDO;

require_once __DIR__ . '/../../config.php';
require_once ROOT_DIR . '/migrations/migrations/Migration.php';


class CompanyMigration extends Migration
{

    public function __construct(PDO $PDO)
    {
        parent::__construct($PDO, 'company');
    }

    public function up()
    {
        $table = $this->table;
        $column = ['id INT AUTO_INCREMENT PRIMARY KEY', 'name VARCHAR(55)', 'phone TEXT', 'city VARCHAR(5)', 'address TEXT',
                'sity TEXT', 'description TEXT', 'logo TEXT', 'map VARCHAR(55)', 'data TEXT', 'email VARCHAR(100)', 'position INT(2)',
                'fb VARCHAR(100) NULL', 'vk VARCHAR(100) NULL', 'tw VARCHAR(100) NULL', 'wa VARCHAR(100) NULL', 'vb VARCHAR(100) NULL',
                'ok VARCHAR(100) NULL', 'tg VARCHAR(100) NULL', 'ins VARCHAR(100) NULL', 'inn VARCHAR(20) NULL DEFAULT 0', 'yb VARCHAR(100) NULL',
                'url VARCHAR(100)', 'yell VARCHAR(25) NULL', 'imgMini VARCHAR(100)', 'flamp VARCHAR(20) NULL', 'dev VARCHAR(7) NULL',
                'description_hr TEXT', 'rating_hr DOUBLE', 'otrude_id INT(11) NULL', 'email_hr VARCHAR(100) NULL'];
        $column = implode(', ', $column);
        $this->PDO->exec("CREATE TABLE IF NOT EXISTS $table ($column) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    }

}