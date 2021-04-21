<?php include "function.php"; //Управляющий файл

  $id = $_GET['id'];
  if(!is_numeric($id)) header('Location: /404');

  $query = $PDO->query("SELECT * FROM `company` WHERE `id` = $id LIMIT 1");
  $row   = $query->fetch();

  if($query->rowCount() == 0) header('Location: /404');
?>

<!DOCTYPE html>
<html lang="ru-Ru" dir="ltr">
 <head>
   <meta charset="utf-8">
   <title>Добавить компанию</title>
   <link rel="stylesheet" href="/css/admin.css?v=0.0.1">
   <link rel="stylesheet" href="/css/formstyler.css">
   <link rel="stylesheet" href="/css/formstyler.theme.css">
   <link rel="stylesheet" href="/css/trumbowyg.min.css">
   <script type="text/javascript" src="/js/jquery.js"></script>
 </head>
 <body>
  <div class="page__layout">
    <?php include 'modules/menu.php' ?>
    <div class="content">
      <div class="block">
        <h1 class="block__title">Редактировать компанию "<?=$row['name']?>"</h1>
        <div class="login__error"></div>
        <form method="" action="" class="create" data-type="2" onsubmit="return false;">
          <input type="hidden" name="id" value="<?=$row['id']?>" data-check="true">
          <input type="hidden" name="hash" value="<?=substr(md5($row['id']), 0, 8)?>" data-check="true">
          <div class="create__item">
            <p>
              <span>Название компании</span>
              <input type="text" name="name" value="<?=$row['name']?>" data-check="true">
            </p>
            <p>
              <span>ИНН</span>
              <input type="text" name="inn" value="<?=$row['inn']?>">
            </p>
            <p>
              <span>Контактный номер</span>
              <input type="text" name="phone" value="<?=$row['phone']?>" data-check="true">
            </p>
            <p>
              <span>Сайт компании</span>
              <input type="text" name="sity" value="<?=$row['sity']?>" data-check="true">
            </p>
            <p>
              <span>E-mail компании</span>
              <input type="text" name="email" value="<?=$row['email']?>" data-check="true">
            </p>
            <p>
              <span>Позиция</span>
              <select class="width-auto" id="position" name="position">
                <option value="0" <? echo $row['position']== 0?'selected':'';?> >По умолчанию</option>
                <option value="1" <? echo $row['position']== 1?'selected':'';?>>Первое(1) место</option>
                <option value="2" <? echo $row['position']== 2?'selected':'';?>>Второе(2) место</option>
                <option value="3" <? echo $row['position']== 3?'selected':'';?>>Третье(3) место</option>
                <option value="4" <? echo $row['position']== 4?'selected':'';?>>Четвёртое(4) место</option>
                <option value="5" <? echo $row['position']== 5?'selected':'';?>>Пятое(5) место</option>
                <option value="6" <? echo $row['position']== 6?'selected':'';?>>Шестое(6) место</option>
                <option value="7" <? echo $row['position']== 7?'selected':'';?>>Седьмое(7) место</option>
                <option value="8" <? echo $row['position']== 8?'selected':'';?>>Восьмое(8) место</option>
                <option value="9" <? echo $row['position']== 9?'selected':'';?>>Девятое(9) место</option>
                <option value="10" <? echo $row['position']== 10?'selected':'';?>>Десятое(10) место</option>

              </select>
            </p>
            <p>
              <span>Город</span>
              <select class="width-auto" id="city" name="city" data-check="true">
                <option value="1" <? echo $row['sity']== 1?'selected':'';?> >Москва и Московская обл.</option>
                <option value="2" <? echo $row['sity']== 2?'selected':'';?>>Тула и Тульская обл.</option>
                <option value="3" <? echo $row['sity']== 3?'selected':'';?>>Республика Крым</option>
              </select>
            </p>
            <p>
              <span>Адрес офиса</span>
              <input type="text" name="address" value="<?=$row['address']?>" data-check="true">
            </p>
            <p>
              <span>Место нахождения офиса</span>
              <div id="map" class="map"></div>
              <input type="hidden" name="map" value="<?=$row['map']?>" data-check="true">
            </p>
            <p class="checkbox_wraper">
              <label class="label--checkbox">
                <input type='checkbox' class="checkbox" name="davCompany" <?=$row['dev']==1?"checked":"";?>/>
                В разработке
              </label>
            </p>
          </div>
          <div class="create__item" style="padding-left:25px;">
            <p>
              <span>ID компании на сайте(yell.ru)</span>
              <input type="text" name="yell" value="<?=$row['yell']?>">
            </p>
            <p>
              <span>ID компании на сайте(flamp.ru)</span>
              <input type="text" name="flamp" value="<?=$row['flamp']?>">
            </p>
            <p>
              <span>Дата основания компании(DD.MM.YYYY)</span>
              <input type="text" name="data" value="<?=date('d.m.Y',$row['data'])?>" data-check="true">
            </p>
            <p>
              <span>Логотип компании</span>
              <input type="file" name="file" id="create__file" accept=".jpg, .jpeg, .png"/>
            </p>
            <p>
              <span>Facebook(Ссылка)</span>
              <input type="text" name="fb" value="<?=$row['fb']?>">
            </p>
            <p>
              <span>Однокласники(Ссылка)</span>
              <input type="text" name="ok" value="<?=$row['ok']?>">
            </p>
            <p>
              <span>Вконтакте(Ссылка)</span>
              <input type="text" name="vk" value="<?=$row['vk']?>">
            </p>
            <p>
              <span>WhatsApp(сылка)</span>
              <input type="text" name="wa" value="<?=$row['wa']?>">
            </p>
            <p>
              <span>Viber(сылка)</span>
              <input type="text" name="vb" value="<?=$row['tw']?>">
            </p>
            <p>
              <span>Telegram(сылка)</span>
              <input type="text" name="tg" value="<?=$row['tg']?>">
            </p>
            <p>
              <span>Twitter(Ссылка)</span>
              <input type="text" name="tw" value="<?=$row['tw']?>">
            </p>
            <p>
              <span>Instagram(ник)</span>
              <input type="text" name="ins" value="<?=$row['ins']?>">
            </p>
            <p>
              <span>Youtube(Ссылка)</span>
              <input type="text" name="yb" value="<?=$row['yb']?>">
            </p>
          </div>
          <p style="display:block; order:4px; width:100%; margin-left:30px;">
            <span>Описание</span>
            <textarea name="description" data-check="true" class="edit-input__text"><?=$row['description']?></textarea>
          </p>
          <p style="display:block; order:4px; width:100%; margin-left:30px;">
            <span>Описание для рейтинга HR</span>
            <textarea name="description_hr" data-check="true" class="edit-input__text-hr"><?=$row['description_hr']?></textarea>
            <input type="submit" name="" value="Сохранить" class="submit">
          </p>
        </form>
      </div>
    </div>
  </div>
  <script src="/js/trumbowyg.min.js"></script>
  <script src="/js/trumbowyg.upload.min.js"></script>
  <script src="/js/ru.js"></script>
  <script>
    $('.edit-input__text, .edit-input__text-hr').trumbowyg({
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
    });
  </script>
  <script src="https://api-maps.yandex.ru/2.1/?apikey=a6da9518-c21f-41f1-80f6-8fb6ae90ea25&lang=ru_RU" type="text/javascript"></script>
  <script src="/js/formstyler.js"></script>
  <script src="/js/maps.js"></script>
  <script> ymaps.ready(adminMap([<?=$row['map']?>], 17, 'point')); </script>
  <script src="/js/admin.js"></script>
 </body>
</html>
