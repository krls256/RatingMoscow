<?php


namespace migrations\seeders;

use PDO;

require_once __DIR__ . '/../../config.php';
require_once ROOT_DIR . '/migrations/seeders/Seeder.php';

class ReviewSeeder extends Seeder
{
    private $rows;
    private $table;
    private $companies;

    public function __construct(PDO $PDO, $rows, $table,  $companies)
    {
        parent::__construct($PDO);
        $this->table = $table;
        $this->rows = $rows;
        $this->companies = $companies;
    }

    public function run()
    {
        $companiesFromDB = $this->getAddedCompanies();
        $DBCompaniesMap = [];

        foreach ($this->companies as $company) {
            $name = $company['name'];
            $key = $company['id'];

            $DBCompaniesMap[$key] =  $companiesFromDB[$name];
        }
        foreach ($this->rows as $k => $v) {
            $id_com = $this->rows[$k]['id_com'];
            if(!isset($DBCompaniesMap[$id_com])) {
                continue; // Есть отзывы удаленных компаний например Domeo
            }
            $this->rows[$k]['id_com'] = $DBCompaniesMap[$id_com];
        }
        $perInsert = 350;
        $rows = $this->rows;
        while (count($rows) > 0) {

            $slice = array_splice($rows, 0, $perInsert);

            $sql = '';
            $table = $this->table;
            $prepareArray = [];

            foreach ($slice as $ind => $row)
            {
                $prepareArrayCurrent = [];
                $column = array_keys($row);
                $column = array_splice($column, 1);
                $values = array_values($row);
                $values = array_splice($values, 1);

                $columnStr = implode(', ', $column);


                foreach($values as $index => $value) {
                    $k = ':'. $ind .'_'. $column[$index];
                    $prepareArray[$k] = $value;
                    $prepareArrayCurrent[$k] = $value;
                }
                $prepareStr = implode(', ', array_keys($prepareArrayCurrent));

                $sql .= "INSERT INTO $table ($columnStr) VALUES ($prepareStr); \n";

            }
            $req = $this->PDO->prepare($sql);
            $req->execute($prepareArray);

            echo $perInsert . ' отзывов успешно добавлено' . "\n";
        }

    }

    public function getAddedCompanies() {
        $sql = "SELECT id, name FROM company";
        $req = $this->PDO
            ->prepare($sql);

        $req->execute();
        $res = $req->fetchAll();
        $companiesMap = [];

        foreach ($res as $company)
        {
            $k = $company['name'];
            $v = $company['id'];
            $companiesMap[$k] = $v;
        }
        return $companiesMap;
    }

}