<?php
  include 'function.php';

  $id   = (int) $_GET['id'];
  $type = $_GET['type'];
  if($id){

    $db = ($type == 'hr') ? 'review_hr' : 'review';
    $rev  = $PDO->prepare("SELECT * FROM $db WHERE  `id` = ? LIMIT 1");
    $rev->execute(array($id));

    //Если отзыв не найден
    if($rev->rowCount() == 0)
      header('Location: /404');

    $row = $rev->fetch();

    $moderation =  $row['moderation']; //Опубликованый или нет. 0 - на мадерации, 1 - опубликован
  }else{
    header('Location: /404');
  }
?><!DOCTYPE html>
<html lang="ru-Ru" dir="ltr">
 <head>
   <meta charset="utf-8">
   <title>Добавить компанию</title>
   <link rel="stylesheet" href="/css/admin.css?<?=time()?>">
   <link rel="stylesheet" href="/css/formstyler.css">
   <link rel="stylesheet" href="/css/formstyler.theme.css">
   <script type="text/javascript" src="/js/jquery.js"></script>
 </head>
 <body>
  <div class="page__layout">
    <?php include 'modules/menu.php' ?>
    <div class="content">
      <div class="block">
        <div class="block__title">Редактирование отзывов</div>
        <div class="login__error"></div>
          <form class="review-edit" action="" method="post" data-type="<?php echo $db;?>">
            <input type="hidden" name="id" value="<?=$row['id']?>">
            <p>
              <span>Автор</span>
              <input type="text" name="fio" value="<?php echo (!$type == 'hr') ? $row['fio'] : $row['position']?>">
            </p>
            <?php if (!$type == 'hr') { ?>
            <p>
              <span>Email</span>
              <input type="text" name="email" value="<?=$row['email']?>">
            </p>
            <p>
              <span>Номер заказа</span>
              <input type="text" name="service" value="<?=$row['service']?>">
            </p>
            <?php } ?>
            <p>
              <span>Дата публикации</span>
              <input type="text" name="data" value="<?=date('d.m.Y G:i',$row['data'])?>">
            </p>
            <?php if (!$type == 'hr') { ?>
            <p>
              <span>Тип ремонта</span>
              <select class="width-auto" id="type" name="type">
                <option value="1" <?=$row['type']==1?'selected':''?>>Косметический ремонт квартиры</option>
                <option value="2" <?=$row['type']==2?'selected':''?>>Капитальный ремонт квартиры</option>
                <option value="3" <?=$row['type']==3?'selected':''?>>Элитный ремонт квартир</option>
                <option value="4" <?=$row['type']==4?'selected':''?>>Дизайн проект квартиры</option>
              </select>
            </p>
            <?php } ?>
            <p>
              <span>Тип ремонта</span>
              <textarea name="review" rows="8" cols="80"><?=$row['text']?></textarea>
            </p>
            <?php if (!$type == 'hr') { ?>
            <p>
              <span>Позиция отзыва</span>
              <select class="width-auto" id="type" name="pos">
                <option value="0" <?=$row['pos']==0?'selected':''?>>По умолчанию</option>
                <option value="1" <?=$row['pos']==1?'selected':''?>>Первое место(1)</option>
                <option value="2" <?=$row['pos']==2?'selected':''?>>Второе место(2)</option>
                <option value="3" <?=$row['pos']==3?'selected':''?>>Третье место(3)</option>
                <option value="4" <?=$row['pos']==4?'selected':''?>>Четвёртое место(4)</option>
                <option value="5" <?=$row['pos']==5?'selected':''?>>Пятое место(5)</option>
                <option value="6" <?=$row['pos']==6?'selected':''?>>Шестое место(6)</option>
                <option value="7" <?=$row['pos']==7?'selected':''?>>Седьмое место(7)</option>
                <option value="8" <?=$row['pos']==8?'selected':''?>>Восьмое место(8)</option>
                <option value="9" <?=$row['pos']==9?'selected':''?>>Девятое место(9)</option>
                <option value="10" <?=$row['pos']==10?'selected':''?>>Десятое место(10)</option>
              </select>
            </p>
            <?php } ?>
            <div>
              <?if($moderation == 0){?>
                <input class="submit save-review" style="margin:20px 0px;" type="submit" data-method="0" value="Сохранить">
                <input class="submit save-review" style="margin:20px 0px;" type="submit" data-method="1" value="Опубликовать">
              <?}else{?>
                <input class="submit save-review" style="margin:20px 0px;" type="submit" data-method="1" value="Обновить">
                <input data-id="<?=$row['id']?>" data-key="<?=substr(md5($row['id']), 0, 8);?>" data-type="1" class="submit save-dal" type="submit" value="Удалить">
              <?}?>
            </div>
          </form>
      </div>
    </div>
  </div>
  <script src="/js/formstyler.js"></script>
  <script src="/js/admin.js?<?=time()?>"></script>
 </body>
</html>
