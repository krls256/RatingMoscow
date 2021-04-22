<?php
  include "function.php"; //подключаем файл обработчик

  $type      = trim( $_GET['type'] ); //Тип страницы
  $id_filter = (int) ($_GET['id'] ?? null); //Id компании для фильтрации
  $page      = (int) ($_GET['page'] ?? null); //Номера страниц
  $show = 20;

  if( !is_numeric( $page ) or $page < 1 ) $page = 1;
  if( $type != 'flamp' and  $type != 'yell' and $type != 'user' and $type != 'comment')
    header('location:/404');

  //проверяем существует ли фильтр
  if( empty( $_GET['id'] ) or $id_filter == '' or $id_filter == 0){
    $id_filter  = '';
    $sql_filter = '';
  }else{
    $sql_filter = 'and `id_com` ='.$id_filter;
    $id_page    = '&id='.$id_filter;
  }

  $totalQ  = $PDO->prepare( "SELECT count( CASE WHEN `rev`=2 THEN 1 ELSE NULL END ) as `neg`, count( CASE WHEN `rev`=1 THEN 1 ELSE NULL END ) as `pos`FROM `review` WHERE `moderation` = 0 and `view` = ? $sql_filter");
  $totalQ->execute(array($type));
  $total   = $totalQ->fetch();

  //Количество страниц
  $pages = max($total['pos'], $total['neg'])/$show;
  $pages = ceil($pages);
  $pages++;

  if ($page>$pages) $page = 1;
  if (!isset($list)) $list = 0;

  $list=--$page*$show;

  //выводим данные в зависимости от типа страницы
  if( $type == 'flamp' or  $type == 'yell' or $type == 'user'){
    //получаем положительные отзывы
    $rev1 = $PDO->prepare("SELECT * FROM `review` WHERE `moderation` = 0 and `view` = ? and `rev` = 1 $sql_filter ORDER BY `data` DESC LIMIT $show OFFSET $list");
    $rev1->execute(array($type));

    //получаем положительные отзывы
    $rev2 = $PDO->prepare("SELECT * FROM `review` WHERE `moderation` = 0 and `view` = ? and `rev` = 2 $sql_filter ORDER BY `data` DESC LIMIT $show OFFSET $list");
    $rev2->execute(array($type));
  }else
  if($type == 'comment'){
    $rev  = $PDO->query("SELECT * FROM `comment` WHERE `moderation` = 0" );
  }
  switch ($type) {
    case 'flamp':
      $panelTitle = 'отзывов с FLAMP.RU';
      break;
    case 'yell':
      $panelTitle = 'отзывов с YELL.RU';
      break;
    case 'user':
      $panelTitle = 'отзывов пользователей';
      break;
	  case 'comment':
      $panelTitle = 'комментариев';
      break;
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
 <head>
   <meta charset="utf-8">
   <title>Модерация <?=$panelTitle?></title>
   <link rel="stylesheet" href="/css/admin.css?<?=time()?>">
   <link rel="stylesheet" href="/css/formstyler.css">
   <link rel="stylesheet" href="/css/formstyler.theme.css">
   <script type="text/javascript" src="/js/jquery.js"></script>
 </head>
 <body>
  <div class="page__layout">
    <?php include 'modules/menu.php' ?>
    <div class="content">

      <!--Верхняя панель-->
      <div class="block mod_title" style="padding:15px; width:99.2%">
        <h2>Модерация <?=$panelTitle?></h2>
        <?php if( $type == 'flamp' or  $type == 'yell' or $type == 'user'){ ?>
          <select class="filter_moderation" id="filter_moderation" data-link="<?=$type?>">
            <option value="0">Все</option>
            <?php
            $queryCompany = $PDO->query("SELECT `id`, `name` FROM `company`");

            while($row = $queryCompany->fetch()){
              $com_id = $row['id'];
              global $type;

              $countRewiev = $PDO->prepare("SELECT count(*) FROM `review` WHERE `id_com` = :id and `moderation` = 0 and `view` = :type");
              $countRewiev->execute(array('type' => $type, 'id' => $com_id));

            ?>
              <option value="<?=$row['id']?>" <?=$com_id==$id_filter?'selected':'';?>><?=$row['name']?> (<?=$countRewiev->fetch()[0]?>)</option>
            <?php } ?>

          </select>
        <?php } ?>
      </div>

      <?php if( ($type == 'flamp' or  $type == 'yell' or $type == 'user') and $rev1->rowCount() <= 0 and
	      $rev2->rowCount() <= 0){ ?>
            <div class="block" style="color: #828282; text-align: center; font-size:21px; font-weight: bold;">Отзывов пока нет!</div>
      <?php }else
         if($type == 'comment' and $rev->rowCount() <= 0){ ?>
           <div class="block" style="color: #828282; text-align: center; font-size:21px; font-weight: bold;">Комментариев пока нет!</div>
      <?php } ?>

      <div class="revFlex">
        <?php if( $type == 'flamp' or  $type == 'yell' or $type == 'user'){ ?>

              <!-- Блок отзывов -->
              <!--Положительные отзывы-->
              <div class="revFlex__item">
              <?php while($row = $rev1->fetch()){ ?>
                  <div class="block">
                    <div class="header-mod">
                      <div class="header-mod__user"><?=$row['fio']?> об <span><?=infoCompany($row['id_com'], $PDO)->name?></span></div>
                      <div class="header-mod__reveiw--<? echo $row['rev']==1?'green':'red';?>"><? echo $row['rev']==1?'Положительный':'Отрицательный';?></div>
                    </div>
                    <div class="text-review">
                      <?=$row['text']?>
                      <div class="text-review__li"><span>Тип ремонта:</span><?=typeRemont($row['type'])?></div>
                      <div class="text-review__li"><span>Номер договора:</span><?=$row['service']?></div>
                    </div>
                    <div class="review-bottom">
                      <span><?=date('d.m.Y G:i',$row['data'])?></span>
                      <a href="moderation_edit?id=<?=$row['id']?>" class="revive-edit"></a>
                      <buttom class="submit submit--green" style="margin-left:15px;" data-id="<?=$row['id']?>" data-key="<?=substr(md5($row['id']), 0, 8);?>" data-type="1">Опубликовать</buttom>
                      <buttom class="submit submit--red" data-id="<?=$row['id']?>" data-key="<?=substr(md5($row['id']), 0, 8);?>" data-type="1">Отключить</buttom>
                    </div>
                  </div>
                <?php } ?>
                </div>

                <!--отрицательные отзывы-->
                <div class="revFlex__item">
                <?php while($row = $rev2->fetch()){ ?>
                    <div class="block">
                      <div class="header-mod">
                        <div class="header-mod__user"><?=$row['fio']?> об <span><?=infoCompany($row['id_com'], $PDO)->name?></span></div>
                        <div class="header-mod__reveiw--<? echo $row['rev']==1?'green':'red';?>"><? echo $row['rev']==1?'Положительный':'Отрицательный';?></div>
                      </div>
                      <div class="text-review">
                        <?=$row['text']?>
                        <div class="text-review__li"><span>Тип ремонта:</span><?=typeRemont($row['type'])?></div>
                        <div class="text-review__li"><span>Номер договора:</span><?=$row['service']?></div>
                      </div>
                      <div class="review-bottom">
                        <span><?=date('d.m.Y G:i',$row['data'])?></span>
                        <a href="moderation_edit?id=<?=$row['id']?>" class="revive-edit"></a>
                        <buttom class="submit submit--green" data-id="<?=$row['id']?>" data-key="<?=substr(md5($row['id']), 0, 8);?>" data-type="1" style="margin-left:15px;">Опубликовать</buttom>
                        <buttom class="submit submit--red" data-id="<?=$row['id']?>" data-key="<?=substr(md5($row['id']), 0, 8);?>" data-type="1">Отключить</buttom>
                      </div>
                    </div>
                  <?php } ?>
                  </div>

          <?php }else{ ?>

            <!-- Блок комментариев -->

            <div class="revFlex__item">
              <?php while($row = $rev->fetch()){
                //Кому адресован комментарий
                $id = $row['review'];
                $commentQuery = $PDO->query("SELECT com.name, rev.fio FROM `company` as com, `review` as rev WHERE com.id = rev.id_com and rev.id = $id");
                $commentRow   = $commentQuery->fetch();?>
                <div class="block">
                  <div class="header-mod">
                    <div class="header-mod__user"> <span style="color: #828282;">Польз: </span><?=$commentRow['fio']?> от <span><?=$row['fio']?></span></div>
                    <div class="header-mod__reveiw"><?=$commentRow['name']?></div>
                  </div>
                  <div class="text-review">
                    <?=$row['text']?>
                  </div>
                  <div class="comment-bottom">
                    <span><?=date('d.m.Y G:i',$row['data'])?></span>
                    <buttom class="submit submit--green" data-id="<?=$row['id']?>" data-key="<?=substr(md5($row['id']), 0, 8);?>" data-type="2">Опубликовать</buttom>
                    <buttom class="submit submit--red" data-id="<?=$row['id']?>" data-key="<?=substr(md5($row['id']), 0, 8);?>" data-type="2">Отключить</buttom>
                  </div>
                </div>
              <?php } ?>
            </div>

          <?php } ?>
        </div>
      <?php if( ( $pages-1 ) != 1 and ( $type == 'flamp' or  $type == 'yell' or $type == 'user') ){ ?>
        <div class="page_nav"><?php
          if( $page>=1 ) {
            echo '<a href="moderation?type='.$type.$id_page.'" class="oneLink"></a>'; //На первую
            echo '<a href="moderation?type='.$type.'&page='.$page.$id_page.'" class="nav-prev"></a>'; //Назад
          }

          $сurrent = $page+1; //Текущая страница
          $start = $сurrent-3; //перед текущей
          $end = $сurrent+3; //После текущей

          //Навигация по страницам
          for ($j = 1; $j < $pages; $j++) {
              if ( $j >= $start && $j <= $end) {

                if( $j == ( $page+1 ) )
                  echo '<a href="moderation?type='.$type.'&page='.$j.$id_page.'" class="active">' . $j . '</a>';
                else
                  echo '<a href="moderation?type='.$type.'&page='.$j.$id_page.'">' . $j . '</a>';
              }
            }

            if( $j > $page && ( $page+2 ) < $j) {
              echo '<a href="moderation?type='.$type.'&page=' . ($page+2) . $id_page.'" class="nav-next"></a>';
              echo '<a href="moderation?type='.$type.'&page=' . ($j-1) . $id_page.'" class="lastLimk"></a>';
            } ?>
          </div>
        <?php } ?>
    </div>
  </div>
  <script src="/js/formstyler.js"></script>
  <script src="/js/admin.js?<?=time()?>"></script>
 </body>
</html>
