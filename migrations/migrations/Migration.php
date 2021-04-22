<?php

namespace migrations\migrations;

use PDO;

require_once __DIR__ . '/../../config.php';
require_once ROOT_DIR . '/class/Mysql.php';

abstract class Migration
{
    protected $table;
    protected $PDO;
    // Предполагается что код будет исполнятся через cli
    public function __construct(PDO $PDO, $table) {
        $this->PDO = $PDO;
        $this->table = $table;
    }

    public abstract function up();

    public function down() {
        $table = $this->table;
        $this->PDO->exec("DROP TABLE IF EXISTS $table");
    }
}