<?php include "function.php"; //Управляющий файл?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
 <head>
   <meta charset="utf-8">
   <title>Парсер FLAMP.RU</title>
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
        <h1 class="block__title">Парсер <a href="https://flamp.ru">FLAMP.RU</a></h1>
        <div class="login__error"></div>
        <div class="pars_load"></div>
        <form method="" action="" class="parser" data-type="flamp" onsubmit="return false;">
          <div class="create__item">
            <p>
              <span>Название компании</span>
              <select class="width-auto" id="company" name="company">
                <?php
                 $comName = $PDO->query("SELECT  `name`,`flamp` FROM `company` WHERE flamp != ''");
                 while($row = $comName->fetch()){ ?>
                  <option value="<?=$row['flamp']?>"><?=$row['name']?></option>
                <?php } ?>
              </select>
            </p>
          </div>
          <input type="submit" name="" value="Сохранить" class="submit">
        </form>
      </div>
    </div>
  </div>
  <script src="/js/formstyler.js"></script>
  <script src="/js/admin.js?<?=time()?>"></script>
 </body>
</html>
