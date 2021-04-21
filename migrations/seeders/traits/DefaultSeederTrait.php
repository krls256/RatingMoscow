<?php


namespace migrations\seeders\traits;


trait DefaultSeederTrait
{
    abstract protected function getRows();
    abstract protected function getTable();
    abstract protected function getPDO();

    protected function defaultRun() {
        $sql = '';
        $table = $this->getTable();
        foreach ($this->getRows() as $row)
        {
            $column = array_keys($row);
            $column = array_splice($column, 1);
            $values = array_values($row);
            $values = array_splice($values, 1);
            $values = array_map(function ($item)
            {
                if ((string)((int)$item) === $item)
                {
                    return (int)$item;
                }
                return "'" . $item . "'";
            }, $values);

            $columnStr = implode(', ', $column);
            $valuesStr = implode(', ', $values);

            $sql .= "INSERT INTO $table ($columnStr) VALUES ($valuesStr); \n";
        }

        $this->getPDO()->exec($sql);
    }
}