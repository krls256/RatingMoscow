<?php


namespace migrations\seeders;

use migrations\seeders\traits\DefaultSeederTrait;
use PDO;

require_once __DIR__ . '/../../config.php';
require_once ROOT_DIR . '/migrations/seeders/Seeder.php';
require_once ROOT_DIR . '/migrations/seeders/traits/DefaultSeederTrait.php';

class CommentSeeder extends Seeder
{
    private $rows;
    private $table;
    private $reviews;

    public function __construct(PDO $PDO, $rows, $table,  $reviews)
    {
        parent::__construct($PDO);
        $this->table = $table;
        $this->rows = $rows;
        $this->reviews = $reviews;
    }
    public function run()
    {
        $reviewsFromDB = $this->getAddedReviews();
        $DBReviewsMap = [];

        foreach ($this->reviews as $review) {
            $name = $review['fio'].$review['data'].$review['view'];
            $key = $review['id'];

            $DBReviewsMap[$key] =   $reviewsFromDB[$name];
        }

        foreach ($this->rows as $k => $v) {
            $review_id = $this->rows[$k]['review'];
            if(!isset($DBReviewsMap[$review_id])) {
                unset($this->rows[$k]);
                continue;
            }
            $this->rows[$k]['review'] = $DBReviewsMap[$review_id];
        }
        $this->defaultRun();
    }

    public function getAddedReviews() {
        $sql = "SELECT id, fio, data, view FROM review";
        $req = $this->PDO
            ->prepare($sql);

        $req->execute();
        $res = $req->fetchAll();
        $companiesMap = [];

        foreach ($res as $review) {
            $companiesMap[$review['fio'].$review['data'].$review['view']] = $review['id'];
        }

        return $companiesMap;
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