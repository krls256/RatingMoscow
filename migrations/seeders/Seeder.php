<?php


namespace migrations\seeders;

use PDO;

abstract class Seeder
{
    protected $PDO;

    public function __construct(PDO $PDO) {
        $this->PDO = $PDO;
    }

    abstract public function run();
}