<?php
  include 'function.php';

  $id   = (int) $_GET['id'];
  if($id){
    $rev  = $PDO->prepare("SELECT * FROM `review_hr` WHERE  `id` = ? LIMIT 1");
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
   <title>Редактирование отзыва сотрудника</title>
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
        <div class="block__title">Редактирование отзыва сотрудника</div>
        <div class="login__error"></div>
          <form class="review-edit review-hr-edit" action="" method="post">
            <input type="hidden" name="id" value="<?=$row['id']?>">
            <p>
              <span>Должность</span>
              <input type="text" name="position" value="<?php echo $row['position']?>">
            </p>
            <p>
              <span>Дата публикации</span>
              <input type="text" name="data" value="<?=date('d.m.Y G:i',$row['data'])?>">
            </p>
            <p>
              <span>Тип ремонта</span>
              <textarea name="review" rows="8" cols="80"><?=htmlspecialchars_decode($row['text'])?></textarea>
            </p>
            <div>
              <input class="submit" style="margin:20px 0px; display:inline-block;" type="submit" value="Обновить">
              <button data-id="<?=$row['id']?>" class="submit hr-rev-dal" type="button">Удалить</button>
            </div>
          </form>
      </div>
    </div>
  </div>
  <script src="/js/formstyler.js"></script>
  <script src="/js/admin.js?<?=time()?>"></script>
 </body>
</html>
