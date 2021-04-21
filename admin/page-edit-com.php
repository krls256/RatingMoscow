<?php include "function.php"; //Управляющий файл?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
 <head>
   <meta charset="utf-8">
   <title>Редактирование компаний</title>
   <link rel="stylesheet" href="/css/admin.css">
   <link rel="stylesheet" href="/css/formstyler.css">
   <link rel="stylesheet" href="/css/formstyler.theme.css">
   <script type="text/javascript" src="/js/jquery.js"></script>
 </head>
 <body>
  <div class="page__layout">
    <?php include 'modules/menu.php' ?>
    <div class="content">
      <div class="block">
        <h1 class="block__title">Редактирование компаний</h1>
      </div>
      <?php
        $editCompany = $PDO->query("SELECT *, (select count(*) from `review` where `id_com` = company.id ) as `sort`, if(`position`, `position`, 9999) as 'pos' FROM `company`  ORDER BY `pos` ASC, `sort` DESC");;
        while($row = $editCompany->fetch()){
      ?>
      <div class="block" style="padding:0;">
        <div class="company-edit">
          <div class="company-edit__logo"><img src="<?=$row['logo']?>" /></div>
          <div class="company-edit_dis">
            <a href="edit-company?id=<?=$row['id']?>"><?=$row['name']?></a>
            <span>Дата публикации: <?=date('d.m.Y',$row['data'])?></span>
          </div>
          <div class="company-edit__nav">
            <a href="edit-company?id=<?=$row['id']?>" class="company-edit__edit"></a>
            <a href="#" class="company-edit__del" data-id="<?=$row['id']?>" data-key="<?=substr(md5($row['id']), 0, 8);?>"></a>
          </div>
        </div>
      </div>
      <?}?>
    </div>
  </div>
  <script src="https://api-maps.yandex.ru/2.1/?apikey=a6da9518-c21f-41f1-80f6-8fb6ae90ea25&lang=ru_RU" type="text/javascript"></script>
  <script src="/js/formstyler.js"></script>
  <script src="/js/maps.js"></script>
  <script> ymaps.ready(adminMap([55.750620, 37.617239], 7)); </script>
  <script src="/js/admin.js"></script>
 </body>
</html>
