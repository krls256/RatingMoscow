<?php include "function.php"; //Управляющий файл?>

<!DOCTYPE html>
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
        <h1 class="block__title">Добавить компанию</h1>
        <div class="login__error"></div>
        <form method="" action="" class="create" data-type="1" onsubmit="return false;">
          <div class="create__item">
            <p>
              <span>Название компании</span>
              <input type="text" name="name" data-check="true">
            </p>
            <p>
              <span>Дата основания компании(DD.MM.YYYY)</span>
              <input type="text" name="data" data-check="true">
            </p>
            <p>
              <span>ИНН</span>
              <input type="text" name="inn" data-check="true">
            </p>
            <p>
              <span>Логотип компании</span>
              <input type="file" name="file" id="create__file" accept=".jpg, .jpeg, .png"  data-check="true"/>
            </p>
            <p>
              <span>Контактный номер</span>
              <input type="text" name="phone" data-check="true">
            </p>
            <p>
              <span>Сайт компании</span>
              <input type="text" name="sity" data-check="true">
            </p>
            <p>
              <span>E-mail компании</span>
              <input type="text" name="email" data-check="true">
            </p>
            <p>
              <span>Город</span>
              <select class="width-auto" id="city" name="city" data-check="true">
                <option value="1">Москва и Московская обл.</option>
                <option value="2">Тула и Тульская обл.</option>
                <option value="3">Республика Крым</option>
              </select>
            </p>
            <p>
              <span>Адрес офиса</span>
              <input type="text" name="address" data-check="true">
            </p>
            <p>
              <span>Место нахождения офиса</span>
              <div id="map" class="map"></div>
              <input type="hidden" name="map" data-check="true">
            </p>
            <p class="checkbox_wraper">
              <label class="label--checkbox">
                <input type='checkbox' class="checkbox" name="davCompany"/>
                В разработке
              </label>
            </p>
          </div>
          <div class="create__item">
            <p>
              <span>ID компании на сайте(yell.ru)</span>
              <input type="text" name="yell">
            </p>
            <p>
              <span>ID компании на сайте(flamp.ru)</span>
              <input type="text" name="flamp">
            </p>
            <p>
              <span>Facebook(Ссылка)</span>
              <input type="text" name="fb">
            </p>
            <p>
              <span>Однокласники(Ссылка)</span>
              <input type="text" name="ok">
            </p>
            <p>
              <span>Вконтакте(Ссылка)</span>
              <input type="text" name="vk">
            </p>
            <p>
              <span>WhatsApp(сылка)</span>
              <input type="text" name="wa">
            </p>
            <p>
              <span>Viber(сылка)</span>
              <input type="text" name="vb">
            </p>
            <p>
              <span>Telegram(сылка)</span>
              <input type="text" name="tg">
            </p>
            <p>
              <span>Twitter(Ссылка)</span>
              <input type="text" name="tw">
            </p>
            <p>
              <span>Instagram(ник)</span>
              <input type="text" name="ins">
            </p>
            <p>
              <span>Youtube(Ссылка)</span>
              <input type="text" name="yb">
            </p>
            <p>
              <span>Описание</span>
              <textarea name="description" data-check="true"></textarea>
            </p>
            <input type="submit" name="" value="Сохранить" class="submit">
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="https://api-maps.yandex.ru/2.1/?apikey=a6da9518-c21f-41f1-80f6-8fb6ae90ea25&lang=ru_RU" type="text/javascript"></script>
  <script src="/js/formstyler.js"></script>
  <script src="/js/maps.js"></script>
  <script> ymaps.ready(adminMap([55.750620, 37.617239], 7)); </script>
  <script src="/js/admin.js"></script>
 </body>
</html>
