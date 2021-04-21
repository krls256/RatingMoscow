<?php
  include "function.php";

  $set = setting($PDO);

  //Вырезаем имя
  $name =  multiexplode(array('.','/'), $_GET['name'])[0];
  if($name == '') error404();

	//Выбрасываем недохакеров
	$page = multiexplode(array('.','/'), $_GET['page'])[0];
  if(!is_numeric($page) or $page < 1) $page = 1;
  
  //Фильтруем по положительным и отрицательным
  $like = $func->urlClear($_GET['type']);

	if(isset($_GET['type']) and $like != 'positive' and $like != 'negative'){
    header('Location: /otzyvy-' . $name . '/');
  }else{
	  switch ($like) {
		  case 'positive':
		  	$post = "and rev = 1";
			  break;
		  case 'negative':
			  $post = "and rev = 2";
			  break;
    }
  }

  if($like!='')
    $postfix = "/$like";
  else
    $postfix = '';

	//получаем данные компании
	try{
		$com = $PDO->prepare("SELECT * FROM `company` WHERE `url` =  ?");
		$com->execute(array($name));
	}catch(PDOStatement $e){
		error404();
	}

	if($com->rowCount() <= 0) error404(); //если такой компании нет
  $row = $com->fetch(PDO::FETCH_ASSOC);
  
  if ( ($row['dev'] == 1) && empty($_SESSION['id']) && $_SESSION['id'] == '') {
    errorClose();    
  }

	//общее количество отзывов
	$row_id   = $row['id'];
	$total    = $PDO->query("SELECT `id`  FROM `review` WHERE `id_com`= $row_id and `moderation` = 1 and `text` != '' $post")->rowCount();

	//Количество страниц
	$show   = 10;
  $pages  = $total/$show;
	$pages  = ceil($pages);
  $pages++;

  //Если нет такого листа на который перешел человек, то выдаём заглушку
  if ($page > $pages) error404();

	$list     = --$page*$show;
  $сurrent  = $page+1;  //Текущая страница
  $start    = $сurrent-3; //перед текущей
  $end      = $сurrent+3;   //После текущей

?>
<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
		<title>Отзывы о компании «<?=$row['name']?>» — Рейтинг ремонтных компаний</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="description" content='Актуальные отзывы о компании «<?=$row['name']?>»<? $dp = $page+1; if($page+1!=1){echo " — страница " . $dp;}?>. Почитайте отзывы, напишите свой или оставьте заявку на ремонт квартиры на сайте RATING REMONT.'>
		<?
			//Линки next и prev
    	if ($page+1==1) {
      	echo '<link rel="next" href="https://'.$_SERVER['HTTP_HOST'].'/otzyvy-'.$name.'/2'.$postfix.'" />'; 		 			//следующая
      }else if($page+1 == $pages-1){
				echo '<link rel="prev" href="https://'.$_SERVER['HTTP_HOST'].'/otzyvy-'.$name.'/'.($page+1).$postfix.'"/>';  //Назад
			}else{
				echo '<link rel="next" href="https://'.$_SERVER['HTTP_HOST'].'/otzyvy-'.$name.'/'.($page+2).$postfix.'" />'; //следующая
				echo '<link rel="prev" href="https://'.$_SERVER['HTTP_HOST'].'/otzyvy-'.$name.'/'.($page).$postfix.'"/>';    //Назад
			}

			//Указываем каноникал везде кроме главной
			if (!empty($_GET['page'])) {
				echo '<link rel="canonical" href="https://'.$_SERVER['HTTP_HOST'].'/otzyvy-'.$name.'/"/>';
			}
		?>

		<meta property="og:type" content="website" />
		<meta property="og:site_name" content="Рейтинг ремонтных компаний">
		<meta property="og:title" content="Отзывы о компании «<?=$row['name']?>» — Рейтинг ремонтных компаний" />
		<meta property="og:description" content="Актуальные отзывы о компании «<?=$row['name']?>». Почитайте отзывы, напишите свой или оставьте заявку на ремонт квартиры на сайте RATING REMONT." />
		<meta property="og:url" content="https://<?=$_SERVER['HTTP_HOST']?>/otzyvy-<?=$name?>/" />
		<meta property="og:image" content="https://<?=$_SERVER['HTTP_HOST']?>/images/logo.png" />

    <meta name="yandex-verification" content="<?=$set['ya_code']?>" />

		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
		<link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/css/main.css?<?=time()?>">
    <link rel="stylesheet" href="/css/formstyler.css">
    <script src="/js/jquery.js"></script>
  </head>
  <body>
    <div class="wrapper">
      <? mv_header($set['header']); ?>
      <div class="content">
        <div class="content__item-left">
          <div class="snippet company">
            <div class="company__title">
              <h1>Отзывы о компании <?=$row['name']?><span>Дата основания: <?=date('d.m.Y',$row['data'])?></span></h1>
            </div>
            <div class="compaty__item">
              <div>
                <img src="/<?=$row['logo']?>" alt="<?=$row['name']?>">
              </div>
              <div class="company__attributes">
                <div>
                  <span>Телефон: </span>
                  <span><?=$row['phone']?></span>
                </div>
                <div>
                  <span>Сайт: </span>
                  <span><a href="<?=$row['sity']?>" class="company__sity" target="_blank"><?=$row['sity']?></a></span>
                </div>
                <div>
                  <span>Адрес: </span>
                  <span><?=$row['address']?></span>
                </div>
              </div>
            </div>
            <div class="company__map" id="c_map"></div>
            <div class="company__discription">
              <h2 class="discription_company">Описание компании <?=$row['name']?>: </h2>
              <div><?=$row['description']?></div>
              <div><b>ИНН компании: </b><?=$row['inn']?></div>
              <? if(($row['vk'] or $row['fb'] or $row['ins'] or $row['ok'] or $row['tg'] or $row['vb'] or $row['wa'] or $row['yb'] or $row['tw']) != ''){?>
                <div><b>Соц. сети компании:</b></div>
                <div class="social-network">
                  <? if($row['vk'] !=''){?><a href="<?=$row['vk']?>" class="vk" target="_blank"></a><?}?>
                  <? if($row['fb'] !=''){?><a href="<?=$row['fb']?>" class="fb" target="_blank"></a><?}?>
                  <? if($row['ins'] !=''){?><a href="https://www.instagram.com/<?=$row['ins']?>/" class="ins" target="_blank"></a><?}?>
                  <? if($row['ok'] !=''){?><a href="<?=$row['ok']?>" class="ok" target="_blank"></a><?}?>
                  <? if($row['tg'] !=''){?><a href="<?=$row['tg']?>" class="tg" target="_blank"></a><?}?>
                  <? if($row['vb'] !=''){?><a href="<?=$row['vb']?>" class="vb" target="_blank"></a><?}?>
                  <? if($row['wa'] !=''){?><a href="<?=$row['wa']?>" class="wa" target="_blank"></a><?}?>
                  <? if($row['yb'] !=''){?><a href="<?=$row['yb']?>" class="yb" target="_blank"></a><?}?>
                  <? if($row['tw'] !=''){?><a href="<?=$row['tw']?>" class="tw" target="_blank"></a><?}?>
                </div>
              <?}?>
            </div>
          </div>
          <div class="snippet snippet-title" id="rew_block">
            <h2>Отзывы клиентов о компании <?=$row['name']?></h2>
          </div>
		  <div class="button-panel">
			<button class="company__bottom-green review_modal" data-id="<?=$row['id']?>" data-key="<?=$func->hash($row_id)?>" data-type="review">Написать отзыв</button>
			<button class="company__bottom-green request_modal" data-id="<?=$row['id']?>" data-key="<?=$func->hash($row_id)?>" data-type="request" data-val="rating">Оставить заявку</button>
		  </div>
          <!--review-->
          <?php
            $resultReview = get_review($PDO, $post, $show, $list, $page, $row['id']);
            $countReview  = count_review($PDO, $post, $row['id']);

            if($countReview > 0){
              foreach($resultReview as $commentRow){
                $reviewID  = $commentRow['id'];
            ?>
              <div class="snippet review">
                <div class="review__header">
                  <div class="review__user">
                    <b><?=$commentRow['fio']?></b>
                    <span>оставил(а) отзыв</span>
                    <span>о "<a href="<?=$row['sity'];?>"><?=$row['name']?></a>"</span>
                  </div>
                  <div class="review__like <? echo $commentRow['rev']==1?'positive':'negativ'; ?>">
                    <i></i>
                    <? echo $commentRow['rev']==1?'Рекомендую':'Не рекомендую'; ?>
                  </div>
                </div>
                <div class="review__text">
                  <?=$commentRow['text'];?>
                  <?if($commentRow['service'] != '0'){?><div><b>Договор:</b> <?=$commentRow['service']?></div><?}?>
                  <div><b>Проводимые работы:</b> <?=$func->typeRepairs($commentRow['type'])?></div>
                </div>
                <div class="review__footer">
                  <span><?=date('d.m.Y G:i',$commentRow['data'])?></span>
                  <button type="button" class="company__bottom-green comment_modal" data-id="<?=$commentRow['id']?>" data-key="<?=$func->hash($commentRow['id']);?>" data-type="comment" data-val="rating">КОММЕНТИРОВАТЬ</button>
									<?if(isset($_SESSION['id']) != ''){?><a href="/admin/moderation_edit?id=<?=$reviewID?>"><i></i></a><?}?>
                </div>
                  <!--comment-->
                  <?
                    $comm = $PDO->query("SELECT * FROM `comment` WHERE `review` = $reviewID and `moderation` = 1 and `type` = 'rating'");
                    if($comm->rowCount() > 0){
                  ?>
                    <div class="comments">
                      <div class="comments__title">Комментарии</div>
                        <?
                          while($commRow = $comm->fetch()){
                        ?>
                          <div class="comments__item">
                            <div class="comments__header">
                              <?=$commRow['fio']?>
                            </div>
                            <div class="comments__text">
                              <?=$commRow['text']?>
                            </div>
                            <div class="comments__footer">
                              <?=date('d.m.Y G:i',$commRow['data'])?>
                            </div>
                          </div>
                        <?}?>
                    </div>
                    <?}?>
                    <!--comment-->
              </div>
            <?}?>
            <!--review-->

            <!--pageSpeed-->
            <?if($total>$show){?>
              <div class="page_nav">
                <?
                  //Ссылки на 1 и на преведущую
                  if ($page>=1) {
                    echo '<a href="/otzyvy-'.$name.'/1'.$postfix.'#rew_block" class="oneLink"></a>'; //На первую
                    echo '<a href="/otzyvy-'.$name.'/'.$page.$postfix.'#rew_block" class="nav-prev"></a>'; //Назад
                  }

                  //три страницы вперед и назад
                  for ($j = 1; $j < $pages; $j++) {
                    if ($j>=$start && $j<=$end) {

                      if ($j==($page+1))
                        echo '<a href="/otzyvy-'.$name.'/' . $j . $postfix . '#rew_block" class="active">' . $j . '</a>';
                      else
                        echo '<a href="/otzyvy-'.$name.'/' . $j . $postfix . '#rew_block">' . $j . '</a>';
                    }
                  }

                  //На следующую и на последнюю
                  if ($j>$page && ($page+2)<$j) {
                    echo '<a href="/otzyvy-'.$name.'/' . ($page+2) . $postfix . '#rew_block" class="nav-next"></a>';
                    echo '<a href="/otzyvy-'.$name.'/' . ($j-1) . $postfix . '#rew_block" class="lastLimk"></a>';
                  }
                ?>
              </div>
            <?}?>
            <!--pageSpeed-->

            <?}else{?>
              <div class="snippet">
                <div class="noReview">
                    <div>У компании нет отзывов! Оставь отзыв первым <i></i></div>
                    <button class="company__bottom-green review_modal" data-id="<?=$row['id']?>" data-key="<?=$key?>" data-type="review">Написать отзыв</button>
                </div>
              </div>
            <?}?>
        </div>
        <?include 'modules/right.php' ?>
      </div>
      <? include 'modules/footer.php'; ?>
    </div>
    <script>function MapInit(){var e=new ymaps.Map("c_map",{center:[<?=$row['map']?>],zoom:17,controls:["routeButtonControl","typeSelector","fullscreenControl","zoomControl"]});e.behaviors.disable("scrollZoom");var o=new ymaps.GeoObject({geometry:{type:"Point",coordinates:[<?=$row['map']?>]}});e.geoObjects.add(o)}window.onload=function(){e=document.createElement("script"),e.src="https://api-maps.yandex.ru/2.1/?apikey=a6da9518-c21f-41f1-80f6-8fb6ae90ea25&lang=ru_RU&onload=MapInit",document.getElementsByTagName("body")[0].appendChild(e)};</script>
    <? include 'modules/scripts.php';?>
  </body>
</html>
