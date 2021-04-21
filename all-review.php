<?php
  require_once "function.php";

  $set = setting($PDO);

  $show = 10;

  //Количество записей
  $count = $PDO->query("SELECT * FROM `review` WHERE `text` != '' and `moderation` = 1");
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

  $sqlDev = ( !empty($_SESSION['id']) and $_SESSION['id'] != '' )?'':'and c.dev IS NULL';

  $commentQuery = $PDO->query("SELECT 
                                    r.id, 
                                    c.dev, 
                                    r.fio, 
                                    r.rev, 
                                    r.service, 
                                    r.text, 
                                    r.type, 
                                    r.moderation, 
                                    r.id_com, 
                                    r.data, 
                                    r.pos 
                                FROM 
                                  `review` r 
                                LEFT OUTER JOIN `company` c ON 
                                  r.id_com = c.id 
                                WHERE 
                                  r.text != '' and r.moderation = 1 $sqlDev
                                ORDER BY r.data DESC LIMIT $show OFFSET $list");

  $сurrent = $page+1;  //Текущая страница
  $start = $сurrent-3; //перед текущей
  $end = $сurrent+3;   //После текущей

  //Форматирование description
  $dp = $page+1;
  $getDes = $set['all_rev_des'];

  if($page+1!=1){
    $echoPage = "— страница " . $dp;
    $echoDes = sprintf($getDes, $echoPage);
  } else {
    $echoDes = str_replace(' %s', "", $getDes);
  }
?>
<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title><?=$set['all_rev_title']?></title>
    <meta name="description" content="<?=$echoDes?>">

    <meta name="yandex-verification" content="<?=$set['ya_code']?>" />

	  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/main.css?<?=time()?>">
    <link rel="stylesheet" href="/css/formstyler.css">
	  <link rel="canonical" href="https://<?=$_SERVER['HTTP_HOST']?>/all-review"/>
  	<?php
          if ($page+1==1) {
              echo '<link rel="next" href="https://'.$_SERVER['HTTP_HOST'].'/all-review?page=2" />'; 		 	  //следующая
          }else if($page+1 == $pages-1){
  			echo '<link rel="prev" href="https://'.$_SERVER['HTTP_HOST'].'/all-review?page='.($page+1).'"/>';  //Назад
  		}else{
  			echo '<link rel="next" href="https://'.$_SERVER['HTTP_HOST'].'/all-review?page='.($page+2).'" />'; //следующая
  			echo '<link rel="prev" href="https://'.$_SERVER['HTTP_HOST'].'/all-review?page='.($page).'"/>';    //Назад
  		}
      ?>

    <script src="js/jquery.js"></script>
  </head>
  <body>
    <div class="wrapper">
      <?php mv_header($set['header']); ?>
      <div class="content">
        <div class="content__item-left">
          <div class="snippet">
            <h1><?=$set['all_rev_h1']?></h1>
            <p class="index__text"><?=$set['all_rev_text']?></p>
          </div>
          <?php
            if($commentQuery->rowCount() > 0){
              while($commentRow = $commentQuery->fetch()){
                $CompanyID  = $commentRow['id_com'];
                $commentID  = $commentRow['id'];

                /*Нужно получить название компании
                  Можно получить из первого запроса,
                  пишу на будущее для раздела "все отзывы"
                */
                $companyQuery = $PDO->query("SELECT * FROM `company` WHERE `id`=$CompanyID LIMIT 1");
                $companyRow   = $companyQuery->fetch();

                switch ($commentRow['type']) {
                  case '1':
                     $type = 'Косметический ремонт квартиры';
                    break;
                  case '2':
                     $type = 'Капитальный ремонт квартиры';
                    break;
                  case '3':
                     $type = 'Элитный ремонт квартир';
                    break;
                  default:
                    $type = 'Дизайн проект квартиры';
                    break;
                }
            ?>
              <div class="snippet review">
                <div class="review__header">
                  <div class="review__user">
                    <b><?=$commentRow['fio']?></b> <span>о компании "<a href="/otzyvy-<?=$companyRow['url'];?>/"><?=$companyRow['name']?></a>"</span>
                  </div>
                  <div class="review__like <?php echo $commentRow['rev']==1?'positive':'negativ'; ?>">
                    <i></i> <?php echo $commentRow['rev']==1?'Рекомендую':'Не рекомендую'; ?>
                  </div>
                </div>
                <div class="review__text">
                  <?=$commentRow['text'];?>
                  <?php if($commentRow['service'] != '0'){ ?><div><b>Договор:</b>
	                  =$commentRow['service']?></div><?php } ?>
                  <div><b>Проводимые работы:</b> <?=$type?></div>
                </div>
                <div class="review__footer all-review__footer">
                  <span><?=date('d.m.Y G:i',$commentRow['data'])?></span>
                  <?php $comm = $PDO->query("SELECT * FROM `comment` WHERE `review` = $commentID and `moderation` = 1"); ?>
                  <span><?=$comm->rowCount()?></span>
                  <a href="/otzyvy-<?=$companyRow['url']?>/" class="company__bottom-green">Все отзывы о компании</a>
                </div>
              </div>
            <?php } ?>
            <div class="page_nav">
              <?php
                 if ($page>=1) {
                    echo '<a href="/all-review?page=1" class="oneLink"></a>'; //На первую
                    echo '<a href="/all-review?page='.$page.'" class="nav-prev"></a>'; //Назад
                  }

              $сurrent = $page+1; //Текущая страница
              $start = $сurrent-3; //перед текущей
              $end = $сurrent+3; //После текущей

              for ($j = 1; $j < $pages; $j++) {
                if ($j>=$start && $j<=$end) {

                  if ($j==($page+1))
                    echo '<a href="all-review?page=' . $j . '" class="active">' . $j . '</a>';
                  else
                    echo '<a href="all-review?page=' . $j . '">' . $j . '</a>';
                }
              }

              if ($j>$page && ($page+2)<$j) {
                  echo '<a href="all-review?page=' . ($page+2) . '" class="nav-next"></a>';
                  echo '<a href="all-review?page=' . ($j-1) . '" class="lastLimk"></a>';
              }
              ?></div><?php
          }else{
              ?>
              <div class="snippet">
                <span class="noReview">У компании нет отзывов! Оставь отзыв первым <i></i></span>
              </div>
              <?php
            } ?>
        </div>
        <?php include 'modules/right.php'; ?>
      </div>
      <?php include 'modules/footer.php'; ?>
    </div>
    <?php include 'modules/scripts.php';?>
  </body>
</html>
