<?php
  //Количество отзывов с YELL
  $CRYQ = $PDO->query("SELECT count(*) FROM `review` WHERE `moderation` = 0 and `view` = 'yell'");
  $CRY  = $CRYQ->fetch();

  //Количество отзывов с Flamp
  $CRFQ = $PDO->query("SELECT count(*) FROM `review` WHERE `moderation` = 0 and `view` = 'flamp'");
  $CRF  = $CRFQ->fetch();

  //количество пользовательских отзывов
  $CRUQ = $PDO->query("SELECT count(*) FROM `review` WHERE `moderation` = 0 and `view` = 'user'");
  $CRU  = $CRUQ->fetch();

  //количество коментариев
  $countCommentQuery = $PDO->query("SELECT count(*) FROM `comment` WHERE `moderation` = 0");
  $countComment = $countCommentQuery->fetch();
?>
<div class="menu">
  <a href="/"><div class="menu__logo">RATING <span>REMONT</span></div></a>
  <ul class="menu__link">
    <a href="/admin/create-company"><li>Добавить компанию</li></a>
    <a href="/admin/page-edit-com"><li>Ред. компаний</li></a>
    <li class="harmonic active">
      <div>Модирация отзывов</div>
      <ul>
        <a href="/admin/moderation?type=user"><li>Пользователи<span><?=$CRU[0]?></span></li></a>
        <a href="/admin/moderation?type=yell"><li>YELL<span><?=$CRY[0]?></span></li></a>
        <a href="/admin/moderation?type=flamp"><li>FLAMP<span><?=$CRF[0]?></span></li></a>
      </ul>
    </li>
    <a href="/admin//moderation?type=comment"><li>Комментарии <span><?=$countComment[0]?></span></li></a>
    <a href="/admin/articles"><li>Добавить статью</li></a>
    <a href="/admin/parser"><li>Парсер YELL.RU</li></a>
    <a href="/admin/fparser"><li>Парсер FLAMP.RU</li></a>
  </ul>
  <ul class="menu__link">
    <a href="/"><li>На главную</li></a>
    <a href="/admin/exit"><li>Выйти</li></a>
  </ul>
</div>
