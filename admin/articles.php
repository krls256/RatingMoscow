<?php include "function.php"; //Управляющий файл?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
 <head>
   <meta charset="utf-8">
   <title>Добавить статью</title>
   <link rel="stylesheet" href="/css/admin.css">
   <link rel="stylesheet" href="/css/formstyler.css">
   <link rel="stylesheet" href="/css/formstyler.theme.css">
   <link rel="stylesheet" href="/css/trumbowyg.min.css">
   <script type="text/javascript" src="/js/jquery.js"></script>
 </head>
 <body>
  <div class="page__layout">
    <?php include 'modules/menu.php' ?>
    <div class="content">
      <?php if(isset($_GET['id']) != ''){
         $id = (int) $_GET['id'];

         $query = $PDO->query("SELECT * FROM `articles` WHERE `id` = $id LIMIT 1");
         $row = $query->fetch();
        ?>
        <div class="block">
          <h1 class="block__title">Редактирование статьи</h1>
          <div class="login__error"></div>
          <form class="articals_edit" action="">
            <input type="hidden" name="id" value="<?=$row['id']?>" data-check="true"/>
            <input type="hidden" name="key" value="<?=substr(md5($row['id']), 0, 8);?>" data-check="true"/>
            <p>
              <span>Заголовок стати</span>
              <input type="text" name="title" value="<?=$row['title']?>" data-check="true">
            </p>
            <p>
              <span>Превью статьи</span>
              <input type="file" name="file" id="create__file" accept=".jpg, .jpeg, .png" />
            </p>
            <p>
              <textarea name="text" rows="8" cols="80" class="moderation__artical" data-check="true"><?=$row['text']?></textarea>
            </p>
            <p>
              <span>Автор</span>
              <input type="text" name="autor" value="<?=$row['autor']?>" data-check="true">
            </p>
            <input type="submit" name="" value="Сохранить" class="submit">
          </form>
        </div>
      <?php }else{ ?>
      <div class="block">
        <h1 class="block__title">Добавить статью</h1>
        <div class="login__error"></div>
        <form class="articals" action="">
          <p>
            <span>Заголовок стати</span>
            <input type="text" name="title" value="">
          </p>
          <p>
            <span>Превью статьи</span>
            <input type="file" name="file" id="create__file" accept=".jpg, .jpeg, .png"/>
          </p>
          <p>
            <textarea name="text" rows="8" cols="80" class="moderation__artical"></textarea>
          </p>
          <p>
            <span>Автор</span>
            <input type="text" name="autor" value="">
          </p>
          <input type="submit" name="" value="Сохранить" class="submit">
        </form>
      </div>
      <?php
        $editCompany = $PDO->query("SELECT * FROM `articles` ORDER BY `date` DESC");;
        while($row = $editCompany->fetch()){
      ?>
      <div class="block" style="padding:0;">
        <div class="company-edit">
          <div class="company-edit_dis">
            <a href="/articles/<?=$row['id']?>" style="color:#000;"><?=$row['title']?></a>
            <span>Дата публикации: <?=date('d.m.Y',$row['date'])?></span>
          </div>
          <div class="company-edit__nav">
            <a href="articles?id=<?=$row['id']?>" class="company-edit__edit"></a>
            <a href="#" class="company-edit__del" data-id="<?=$row['id']?>" data-key="<?=substr(md5($row['id']), 0, 8);?>"></a>
          </div>
        </div>
      </div>
      <?php }

      } ?>
    </div>
  </div>

  <script src="/js/trumbowyg.min.js"></script>
  <script src="/js/trumbowyg.upload.min.js"></script>
  <script src="/js/ru.js"></script>
  <script>
    $('.moderation__artical').trumbowyg({
      lang: 'ru',
      svgPath: '../images/icons.svg',
      btnsDef: {
        // Create a new dropdown
        image: {
            dropdown: ['insertImage', 'upload'],
            ico: 'insertImage'
        }
      },
      btns: [
        ['viewHTML'],
        ['formatting'],
        ['strong', 'em', 'del'],
        ['superscript', 'subscript'],
        ['link'],
        ['image'], // Our fresh created dropdown
        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
        ['unorderedList', 'orderedList'],
        ['horizontalRule'],
        ['removeformat'],
        ['fullscreen']
    ],
    plugins: {
        // Add imagur parameters to upload plugin for demo purposes
        upload: {
            serverPath: 'https://api.imgur.com/3/image',
            fileFieldName: 'image',
            headers: {
                'Authorization': 'Client-ID fecead0231558d7'
            },
            urlPropertyName: 'data.link'
        }
    }
    });</script>
  <script src="/js/formstyler.js"></script>
  <script src="/js/admin.js"></script>
 </body>
</html>
