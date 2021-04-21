<?php
  header('Content-Type: text/xml');

  require_once "function.php";

  $set = setting($PDO);

  $protocol = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://';
  $URL = $protocol . $_SERVER['SERVER_NAME'];
?>
<rss xmlns:yandex="http://news.yandex.ru"
     xmlns:media="http://search.yahoo.com/mrss/"
     xmlns:turbo="http://turbo.yandex.ru"
     version="2.0">
    <channel>
      <turbo:analytics type="Yandex" id="<?=$set['ya_metriks']?>"></turbo:analytics>
      <item turbo="true">
        <title><?=$set['title']?></title>
        <link><?=$URL?></link>
        <turbo:content>
          <header>
            <h1><?=$set['h1']?></h1>
            <menu>
              <a href="<?=$URL?>/" data-turbo="true">Главная</a>
              <a href="<?=$URL?>/all-review" data-turbo="true">Все отзывы</a>
              <a href="<?=$URL?>/articles" data-turbo="true">Статьи</a>
              <a href="<?=$URL?>/contacts" data-turbo="true">Контакты</a>
            </menu>
          </header>
          <blockquote>
            <p><?=$set['index_text']?></p>
          </blockquote>
          <table>
            <tr>
              <th>№</th>
              <th>Название</th>
            </tr>
            <?php
              $com = $PDO->query("SELECT *, 
                                        (select count(*) from `review` where `id_com` = company.id ) as `sort`, 
                                        if(`position`, `position`, 9999) as 'pos' 
                                      FROM  `company`  WHERE 
                                        `dev` IS NULL 
                                      ORDER BY `pos` ASC, `sort` DESC");
              $i = 1;
              while ($row = $com->fetch()) {
                ?>
                <tr>
                  <th><?=$i?></th>
                  <th>
                    <a href="<?=$URL?>/otzyvy-<?=$row['url']?>/" class="rating-img"><img src="<?=$URL?>/<?=$row['imgMini']?>"/></a>
                    <a class="table-center" href="<?=$URL?>/otzyvy-<?=$row['url']?>/"><?=$row['name']?></a>
                  </th>
                </tr>
                <?
                $i++;
              }
            ?>
          </table>
          <h2>Поиск ремонтной компании с помощью рейтинга</h2>
          <p class="border-bottom">
            Когда встает вопрос ремонта, многие люди обращаются в ремонтные компании. У хозяев нет времени и опыта самостоятельно заниматься всеми процедурами. Но тут возникает другая проблема: как найти надежную организацию, которая сделает все аккуратно и вовремя. Тогда на помощь приходит рейтинг.
          </p>
          <h2>Как мы определяем топ компаний по ремонту квартир?</h2>
          <p>
            Когда встает вопрос ремонта, многие люди обращаются в ремонтные компании. У хозяев нет времени и опыта самостоятельно заниматься всеми процедурами. Но тут возникает другая проблема: как найти надежную организацию, которая сделает все аккуратно и вовремя. Тогда на помощь приходит рейтинг.
          </p>
        </turbo:content>
      </item>

      <!--Cтраницы компаний-->
      <?
        $cmpanyQuery = $PDO->query("SELECT * FROM `company` WHERE `dev` IS NULL");

        while($row = $cmpanyQuery->fetch()) {
          ?>
          <item turbo="true">
          <title>Отзывы о компании «<?=$row['name']?>» — Рейтинг ремонтных компаний</title>
          <link><?=$URL.'/otzyvy-'.$row['url'].'/'?></link>
          <turbo:content>
            <header>
              <h1>Отзывы о компании <?=$row['name']?></h1>
              <menu>
                <a href="<?=$URL?>/" data-turbo="true">Главная</a>
                <a href="<?=$URL?>/all-review" data-turbo="true">Все отзывы</a>
                <a href="<?=$URL?>/articles" data-turbo="true">Статьи</a>
                <a href="<?=$URL?>/contacts" data-turbo="true">Контакты</a>
              </menu>
            </header>
            <blockquote>
              <p>Дата основания: <?=date('d.m.Y', $row['data'])?></p>
            </blockquote>
            <blockquote>
              <img class="img_company" src="<?=$URL.'/'.$row['logo']?>"/>
              <p><b>Телефон: </b><?=$row['phone']?></p>
              <p><b>Сайт: </b><a href="<?=$row['sity']?>" data-turbo="false"><?=$row['name']?></a></p>
              <p><b>Адрес: </b><?=$row['address']?></p>
            </blockquote>
            <? if( $row['vk'] !='' or $row ['fb'] !='' or $row['tw'] !='' or $row['ok'] !='' or $row['tg'] !='' or $row['vb'] !='' or $row['wa'] !='') { ?>
              <div data-block="widget-feedback" data-title="Cоц. сети">
                <? if($row['vk'] !=''){?><div data-type="vkontakte" data-url="<?=$row['vk']?>"></div><?}?>
                <? if($row['fb'] !=''){?><div data-type="facebook" data-url="<?=$row['fb']?>"></div><?}?>
                <? if($row['tw'] !=''){?><div data-type="twitter" data-url="<?=$row['tw']?>"></div><?}?>
                <? if($row['ok'] !=''){?><div data-type="odnoklassniki" data-url="<?=$row['ok']?>"></div><?}?>
                <? if($row['tg'] !=''){?><div data-type="telegram" data-url="<?=$row['tg']?>"></div><?}?>
                <? if($row['vb'] !=''){?><div data-type="viber" data-url="<?=$row['vb']?>"></div><?}?>
                <? if($row['wa'] !=''){?><div data-type="whatsapp" data-url="<?=$row['wa']?>"></div><?}?>
              </div>
            <?}?>
            <button formaction="<?=$URL.'/otzyvy-'.$row['url'].'/'?>"
              data-background-color="#00afff"
              data-color="white"
              data-turbo="false"
              data-primary="true">Оставить заявку</button>
              <?
                $reviewQuery = $PDO->prepare("SELECT * FROM `review` WHERE `id_com`= ? and `moderation` = ? and `text` != '' ORDER BY `data` DESC LIMIT 10");
                $reviewQuery->execute(array($row['id'], 1));

                $id = $row['id']; 
                $reviewCount = $PDO->query("SELECT * FROM `review` WHERE `id_com`= $id and `moderation` = 1 and `text` != ''")->rowCount();


                if($reviewQuery->rowCount() > 0){?>
                  <div data-block="comments" data-url="<?=$URL.'/'.$row['url']?>/">
                  <? 
                    while($j = $reviewQuery->fetch()){
                  ?>
                    <div  data-block="comment" 
                      data-author="<?=$j['fio']?>"
                      data-avatar-url="<?=$j['rev']==1?$URL.'/images/happy.svg':$URL.'/images/sad.svg';?>"
                      data-subtitle="<?=$j['rev']==1?'Рекомендую':'Не рекомендую';?>">
                      <div data-block="content">
                        <p><?=$j['text']?></p>
                      </div>
                    </div>
                    <?}?>
                  <!--Конец-->
                  </div>
              <?}else{?>
                <blockquote>Отзывов нет!</blockquote>
              <?}?>
              
              <?if($reviewCount > 10){?>
              <button formaction="<?=$URL.'/otzyvy-'.$row['url'].'/2'?>"
                      data-background-color="#00afff"
                      data-color="white"
                      data-turbo="true"
                      data-primary="true">Дальше →</button>
              <?}?>
          </turbo:content>
        </item>
          <?
        }
        ?>
        <!--Cтраницы компаний-->
        <item turbo="true">
          <title><?=$set['contact_title']?></title>
          <link><?=$URL.'/contacts'?></link>
          <turbo:content>
            <header>
              <h1>Контакты. ТОП-Рейтинг ремонт квартир.</h1>
              <menu>
                <a href="<?=$URL?>/" data-turbo="true">Главная</a>
                <a href="<?=$URL?>/all-review" data-turbo="true">Все отзывы</a>
                <a href="<?=$URL?>/articles" data-turbo="true">Статьи</a>
                <a href="<?=$URL?>/contacts" data-turbo="true">Контакты</a>
              </menu>
            </header>
            <blockquote>
              <p><b>О нас</b></p>
              <p>Наш сайт создан для быстрого и честного поиска компании занимающейся ремонтом квартир и домов. На данном ресурсе собрана информация и отзывы о ремонтных компаниях <?=$set['contact_city']?>.</p>
            </blockquote>
            <blockquote>
              <p><b>*Внимание</b> Используя наш ресурс вы автоматически соглашаетесь с правилами и условиями испльзования нашего ресурса.</p>
              <a href="<?=$URL?>/upload/pologenie_sayta.doc">Ознакомиться с положением вы можете по данной ссылке rating-remont.moscow</a>
            </blockquote>
            <blockquote>
              <p><b>Связь с нами</b></p>
              <p>Все вопросы или претензии вы может написать нам на нашу электронную почту.</p>
              <p><b>Email:</b> <a href="mailto:info@rating-remont.moscow">info@rating-remont.moscow</a></p>
            </blockquote>
          </turbo:content>
        </item>
        <!--Список статей-->
        <item turbo="true">
          <title><?=$set['articles_title']?></title>
          <link><?=$URL.'/articles'?></link>
          <turbo:content>
            <header>
              <h1><?=$set['articles_h1']?></h1>
              <menu>
                <a href="<?=$URL?>/" data-turbo="true">Главная</a>
                <a href="<?=$URL?>/all-review" data-turbo="true">Все отзывы</a>
                <a href="<?=$URL?>/articles" data-turbo="true">Статьи</a>
                <a href="<?=$URL?>/contacts" data-turbo="true">Контакты</a>
              </menu>
            </header>
            <blockquote>
              <p><b>Статьи</b></p>
              <p><?=$set['articles_text']?></p>
            </blockquote>
            <?php 
              $articles = $PDO->query("SELECT * FROM `articles` ORDER BY `date` DESC");

              while($articlesRow = $articles->fetch()){
            ?>
              <blockquote>
                <a href="<?=$URL.'/articles/'.$articlesRow['id']?>"><?=$articlesRow['title']?></a>
                <p class="articles_autor"><?=$articlesRow['autor']?></p>
                <p><![CDATA[<?php echo $func->crop($articlesRow['text'], 285);?>]]></p>
                <button formaction="<?=$URL.'/articles/'.$articlesRow['id']?>"
                    data-background-color="#00afff"
                    data-color="white"
                    data-turbo="true"
                    data-primary="true">Читать</button>
              </blockquote>
            <?}?>

          </turbo:content>
        </item>
        <!--Список статей-->

        <!--Сама статья-->
        <?php 
          $articles = $PDO->query("SELECT * FROM `articles` ORDER BY `date` DESC");

          while($get_articles_row = $articles->fetch()){
        ?>
        <item turbo="true">
          <title>Читать статью — <?=$get_articles_row['title']?></title>
          <link><?=$URL.'/articles/'.$get_articles_row['id']?></link>
          <turbo:content>
            <header>
              <h1><?=$get_articles_row['title']?></h1>
              <menu>
                <a href="<?=$URL?>/" data-turbo="true">Главная</a>
                <a href="<?=$URL?>/all-review" data-turbo="true">Все отзывы</a>
                <a href="<?=$URL?>/articles" data-turbo="true">Статьи</a>
                <a href="<?=$URL?>/contacts" data-turbo="true">Контакты</a>
              </menu>
            </header>
            <blockquote>
            <![CDATA[<p><b><?=$get_articles_row['title']?></b></p>
              <img src="<?=$URL.'/'.$get_articles_row['file']?>" />
              <p><?=str_replace('id="article-buy-text"', '', $get_articles_row['text']);?></p>
              <p class="articles_autor"><?=$get_articles_row['autor']?></p>
              <p class="articles_autor"><?=date('d.m.Y', $get_articles_row['date']);?></p>]]>
            </blockquote>
          </turbo:content>
        </item>
        <?}?>
        <!--Сама статья-->

        <!-- Страницы компаний страницы отзывов -->
        <?php
          $companyQuery = $PDO->query("SELECT * FROM `company`");

          while($rowCom = $companyQuery->fetch()){
            $id = $rowCom['id'];
            $reviewCount = $PDO->query("SELECT * FROM `review` WHERE `id_com`= $id and `moderation` = 1 and `text` != ''")->rowCount();
            $offset = ceil($reviewCount/10);

            for($i=2; $i < ($offset+1); $i++){
              ?>
              <item turbo="true">
                <link><?=$URL.'/otzyvy-'.$rowCom['url'].'/'.$i?></link>
                <turbo:content>
                  <header>
                    <h1>Отзывы о компании «<?=$rowCom['name']?>»</h1>
                    <menu>
                      <a href="<?=$URL?>/" data-turbo="true">Главная</a>
                      <a href="<?=$URL?>/all-review" data-turbo="true">Все отзывы</a>
                      <a href="<?=$URL?>/articles" data-turbo="true">Статьи</a>
                      <a href="<?=$URL?>/contacts" data-turbo="true">Контакты</a>
                    </menu>
                  </header>
                  <blockquote>
                    <p>Дата основания: <?=date('d.m.Y', $rowCom['data'])?></p>
                  </blockquote>
                  <blockquote>
                    <img class="img_company" src="<?=$URL.'/'.$rowCom['logo']?>"/>
                    <p><b>Телефон: </b><?=$rowCom['phone']?></p>
                    <p><b>Сайт: </b><a href="<?=$rowCom['sity']?>" data-turbo="false"><?=$rowCom['name']?></a></p>
                    <p><b>Адрес: </b><?=$rowCom['address']?></p>
                  </blockquote>
                  <div data-block="widget-feedback" data-title="Cоц. сети">
                    <? if($rowCom['vk'] !=''){?><div data-type="vkontakte" data-url="<?=$rowCom['vk']?>"></div><?}?>
                    <? if($rowCom['fb'] !=''){?><div data-type="facebook" data-url="<?=$rowCom['fb']?>"></div><?}?>
                    <? if($rowCom['tw'] !=''){?><div data-type="twitter" data-url="<?=$rowCom['tw']?>"></div><?}?>
                    <? if($rowCom['ok'] !=''){?><div data-type="odnoklassniki" data-url="<?=$rowCom['ok']?>"></div><?}?>
                    <? if($rowCom['tg'] !=''){?><div data-type="telegram" data-url="<?=$rowCom['tg']?>"></div><?}?>
                    <? if($rowCom['vb'] !=''){?><div data-type="viber" data-url="<?=$rowCom['vb']?>"></div><?}?>
                    <? if($rowCom['wa'] !=''){?><div data-type="whatsapp" data-url="<?=$rowCom['wa']?>"></div><?}?>
                  </div>
                  <button formaction="<?=$URL.'/otzyvy-'.$rowCom['url'].'/'.$i?>"
                    data-background-color="#00afff"
                    data-color="white"
                    data-turbo="false"
                    data-primary="true">Оставить заявку</button>
                    <?

                      $reviewQuery = $PDO->prepare("SELECT * FROM `review` WHERE `id_com`= ? and `moderation` = ? and `text` != '' ORDER BY `data` DESC LIMIT 10 OFFSET $i");
                      $reviewQuery->execute(array($rowCom['id'], 1));

                      if($reviewQuery->rowCount() > 0){?>
                        <div data-block="comments" data-url="<?=$URL.'/otzyvy-'.$rowCom['url'].'/'.$i?>/">
                        <? 
                          while($j = $reviewQuery->fetch()){
                        ?>
                          <div  data-block="comment" 
                            data-author="<?=$j['fio']?>"
                            data-avatar-url="<?=$j['rev']==1?$URL.'/images/happy.svg':$URL.'/images/sad.svg';?>"
                            data-subtitle="<?=$j['rev']==1?'Рекомендую':'Не рекомендую';?>">
                            <div data-block="content">
                              <p><?=$j['text']?></p>
                            </div>
                          </div>
                          <?}?>
                        <!--Конец-->
                        </div>
                    <?}else{?>
                      <blockquote>Отзывов нет!</blockquote>
                    <?}?>
                    <? if($i < $offset){ ?>
                      <button formaction="<?=$URL.'/otzyvy-'.$rowCom['url'].'/'.($i+1)?>"
                      data-background-color="#00afff"
                      data-color="white"
                      data-turbo="true"
                      data-primary="true">Дальше →</button>
                    <?}?>
                   
                    <button formaction="<?=$URL.'/otzyvy-'.$rowCom['url'].'/'.($i-1)?>"
                    data-background-color="#0dd149"
                    data-color="white"
                    data-turbo="true"
                    data-primary="true">← Назад</button>
                </turbo:content>
              </item>
      <?}}?>
      <!-- Конец страницы компаний страницы отзывов -->
      
      <?
        $allCountReview = $PDO->query("SELECT * FROM `review` WHERE `moderation` = 1 and `text` != '' ORDER BY `data` DESC");

        $totalRow = $allCountReview->rowCount();

        $pages = ceil($totalRow/10)+1;

        for($i=1; $i < $pages; $i++){

          $page = $i==1?'':'?page='.$i;
      ?>

        <!--Все отзывы-->
        <item turbo="true">
            <title><?=$set['all_rev_title']?></title>
            <link><?=$URL.'/all-review'.$page?></link>
            <turbo:content>
              <header>
                <h1><?=$set['all_rev_h1']?></h1>
                <menu>
                  <a href="<?=$URL?>" data-turbo="true">Главная</a>
                  <a href="<?=$URL?>/all-review" data-turbo="true">Все отзывы</a>
                  <a href="<?=$URL?>/articles" data-turbo="true">Статьи</a>
                  <a href="<?=$URL?>/contacts" data-turbo="true">Контакты</a>
                </menu>
              </header>
              <blockquote>
                <p><b>Все отзывы клиентов</b></p>
                <p><?=$set['all_rev_text']?></p>
              </blockquote>

              <div data-block="comments" data-url="<?=$URL?>/">
                <?
                  $list = ($i-1)*10;
                  $allReview = $PDO->query("SELECT * FROM `review` WHERE `moderation` = 1 and `text` != '' ORDER BY `data` DESC LIMIT 10 OFFSET $list");
                  while($rowAll = $allReview->fetch()){
                    ?>
                      <div  data-block="comment" 
                            data-author="<?=$rowAll['fio']?>"
                            data-avatar-url="<?=$rowAll['rev']==1?$URL.'/images/happy.svg':$URL.'/images/sad.svg';?>"
                            data-subtitle="<?=$rowAll['rev']==1?'Рекомендую':'Не рекомендую';?>">
                        <div data-block="content">
                          <p><?=$rowAll['text']?></p>
                        </div>
                      </div>
                    <?
                  }
                ?>
              </div>

              <?if($i < $pages-1){
                ?>
                  <button formaction="<?=$URL.'/all-review?page='.($i+1)?>"
                      data-background-color="#00afff"
                      data-color="white"
                      data-turbo="true"
                      data-primary="true">Дальше →</button>
                <?
              }
              
              if($i > 1){

                $prev = $i==2?'':'?page='.($i-1);
                ?>
                  <button formaction="<?=$URL.'/all-review'.$prev?>"
                    data-background-color="#0dd149"
                    data-color="white"
                    data-turbo="true"
                    data-primary="true">← Назад</button>
                <?
              }?>
            </turbo:content>
          </item>
        <!--Все отзывы-->
      <?}?>
    </channel>
  </rss>
