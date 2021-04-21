<?php

namespace migrations\migrations;

use PDO;

require_once __DIR__ . '/../../config.php';
require_once ROOT_DIR . '/class/Mysql.php';

abstract class Migration
{
    // Предполагается что код будет исполнятся через cli
    protected $PDO;
    public function __construct(PDO $PDO) {
        $this->PDO = $PDO;
    }

    public abstract function up();

    public abstract function down();
}