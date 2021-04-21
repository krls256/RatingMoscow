<?php
  require_once "function.php";

  $set = setting($PDO);
  $show = 10;

  //Количество записей
  $count = $PDO->query("SELECT `id` FROM `articles`");
  $total = $count->rowCount();

  //Номера страниц
$page = isset($_GET['page']) ? $_GET['page'] : 1;
  if(!is_numeric($page) or $page < 1) $page = 1;

  //Количество страниц
  $pages = $total/$show;
  $pages = ceil($pages);
  $pages++;

  if ($page>$pages) $page = 1;
  if (!isset($list)) $list = 0;

  $list=--$page*$show;

  $articles = $PDO->query("SELECT * FROM `articles` ORDER BY `date` DESC LIMIT $show OFFSET $list");

?>
<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?=$set['articles_title']?></title>
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="description" content="<?=$set['articles_des']?>">

    <meta name="yandex-verification" content="<?=$set['ya_code']?>" />

	  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/css/main.css?<?=time()?>">
    <link rel="stylesheet" href="/css/formstyler.css">
    <script src="/js/jquery.js"></script>
  </head>
  <body>
    <div class="wrapper">
      <?php mv_header($set['header']); ?>
      <div class="content">
        <div class="content__item-left">
          <div class="snippet">
            <h1><?=$set['articles_h1']?></h1>
            <p class="index__text"><?=$set['articles_text']?></p>
          </div>
          <?php
           while ($row = $articles->fetch()) {
             $text = $func->crop($row['text'], 500);
          ?>
          <div class="snippet article-snippet">
            <div class="article-snippet__header" style="background-image: url('<?=$row['file']?>');">
              <div class="article-header">
                <a href="/articles/<?=$row['id']?>"><?=$row['title']?></a>
                <div class="article-header__footer">
                  <p><?=$row['autor']?></p>
                  <span><?=$row['visit']?></span>
                </div>
              </div>
            </div>
            <p><?=$text?></p>
            <div class="article-snippet__footer">
              <p><?=date('d.m.Y G:i', $row['date'])?></p>
              <a href="/articles/<?=$row['id']?>" class="company__bottom-green">Читать</a>
            </div>
          </div>
          <?php } if ($total > $show) { ?>
          <div class="page_nav">
            <?php
               if ($page>=1) {
                  echo '<a href="articles?page=1" class="oneLink"></a>'; //На первую
                  echo '<a href="articles?page='.$page.'" class="nav-prev"></a>'; //Назад
                }

            $сurrent = $page+1; //Текущая страница
            $start = $сurrent-3; //перед текущей
            $end = $сurrent+3; //После текущей

            for ($j = 1; $j < $pages; $j++) {
              if ($j>=$start && $j<=$end) {

                if ($j==($page+1))
                  echo '<a href="articles?page=' . $j . '" class="active">' . $j . '</a>';
                else
                  echo '<a href="articles?page=' . $j . '">' . $j . '</a>';
              }
            }

            if ($j>$page && ($page+2)<$j) {
                echo '<a href="articles?page=' . ($page+2) . '" class="nav-next"></a>';
                echo '<a href="articles?page=' . ($j-1) . '" class="lastLimk"></a>';
            }
            ?></div>
        <?php } ?></div><?php include 'modules/right.php'; ?>
      </div>
      <?php include 'modules/footer.php'; ?>
    </div>
    <?php include 'modules/scripts.php'; ?>
  </body>
</html>
