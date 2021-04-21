<?php


namespace migrations\seeders;
use migrations\seeders\traits\DefaultSeederTrait;
use PDO;

require_once __DIR__ . '/../../config.php';
require_once ROOT_DIR . '/migrations/seeders/Seeder.php';
require_once ROOT_DIR . '/migrations/seeders/traits/DefaultSeederTrait.php';


class SettingsSeeder extends Seeder
{

    private $rows;
    private $table;

    public function __construct(PDO $PDO, $rows, $table)
    {
        parent::__construct($PDO);
        $this->rows = $rows;
        $this->table = $table;
    }

    public function run()
    {
        $this->defaultRun();
    }

    // DEFAULT
    use DefaultSeederTrait;

    protected function getRows()
    {
        return $this->rows;
    }

    protected function getTable()
    {
        return $this->table;
    }

    protected function getPDO()
    {
        return $this->PDO;
    }
}