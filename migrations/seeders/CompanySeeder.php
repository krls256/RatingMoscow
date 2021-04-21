<?php


namespace migrations\seeders;
use migrations\seeders\traits\DefaultSeederTrait;
use PDO;

require_once __DIR__ . '/../../config.php';
require_once ROOT_DIR . '/migrations/seeders/Seeder.php';
require_once ROOT_DIR . '/migrations/seeders/traits/DefaultSeederTrait.php';

class CompanySeeder extends Seeder
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
        $sql = '';
        $table = $this->getTable();
        foreach ($this->getRows() as $row)
        {
            $column = array_keys($row);
            $column = array_splice($column, 1);
            $values = array_values($row);
            $values = array_splice($values, 1);

            $columnStr = implode(', ', $column);
            $prepareArray = [];

            foreach($values as $index => $value) {
                $k = ':' . $column[$index];
                $prepareArray[$k] = $value;
            }
            $prepareStr = implode(', ', array_keys($prepareArray));



            $sql = "INSERT INTO $table ($columnStr) VALUES ($prepareStr);";
            $req = $this->getPDO()->prepare($sql);
            $req->execute($prepareArray);
        }


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