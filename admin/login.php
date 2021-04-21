<?php
  session_start();

  if(isset($_SESSION['id']) and $_SESSION['id'] != ''){
    header('Location: /admin/');
  }
?>

<!DOCTYPE html>
<html lang="ru-Ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Авторизация</title>
    <link rel="stylesheet" href="/css/admin.css?<?=time()?>">
    <script type="text/javascript" src="/js/jquery.js"></script>
  </head>
  <body>
    <div class="login">
      <h1 class="login__logo">RATING <span>REMONT</span></h1>
      <div class="login__error"></div>
      <div class="login__comment">Вход в административную панель</div>
      <form class="login__form" action="" method="post" onsubmit="return false;">
        <input type="text" name="login" placeholder="Логин">
        <input type="password" name="password" placeholder="Пароль">
        <input class="submit" type="submit" value="Войти">
      </form>
    </div>
    <script type="text/javascript" src="/js/admin.js"></script>
  </body>
</html>
