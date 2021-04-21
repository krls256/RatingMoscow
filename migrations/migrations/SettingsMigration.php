<?php


namespace migrations\migrations;

use PDO;

require_once __DIR__ . '/../../config.php';
require_once ROOT_DIR . '/migrations/migrations/Migration.php';

class SettingsMigration extends Migration
{
    private $table;

    public function __construct(PDO $PDO)
    {
        parent::__construct($PDO);
        $this->table = 'setting';
    }

    public function up()
    {
        $table = $this->table;
        $column = ['id INT AUTO_INCREMENT PRIMARY KEY', 'banner VARCHAR(150)', 'name VARCHAR(25)', 'title VARCHAR(90)',
            'description TEXT', 'header VARCHAR(300)', 'h1 TEXT', 'index_text TEXT', 'ya_metriks VARCHAR(20)', 'google_metriks VARCHAR(20)',
            'all_rev_title VARCHAR(120)', 'all_rev_des VARCHAR(300)', 'all_rev_h1 VARCHAR(500)', 'all_rev_text TEXT',
            'articles_title VARCHAR(120)', 'articles_des VARCHAR(160)', 'articles_h1 VARCHAR(255)', 'articles_text TEXT',
            'contact_title VARCHAR(120)', 'contact_des VARCHAR(160)', 'contact_city VARCHAR(100)', 'ya_code VARCHAR(100)'];
        $column = implode(', ', $column);
        $this->PDO->exec("CREATE TABLE IF NOT EXISTS $table ($column) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    }

    public function down()
    {
        $table = $this->table;
        $this->PDO->exec("DROP TABLE IF EXISTS $table");
    }
}