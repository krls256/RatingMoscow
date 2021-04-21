<!DOCTYPE html>
<html lang="ru-Ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Страница не найдена!</title>
  </head>
  <style>
   *{
     margin: 0;
     padding: 0;
   }
    html, body{
      background: #fff;
    }
    .error_404{
      position: absolute;
      max-width: 500px;
      max-height: 500px;
      left:50%;
      top: 50%;
      transform: translate(-50%, -50%);
      color: #989898;
      font-family: 'Arial';
      font-size: 32px;
      font-weight: bold;
    }
    .img{
      display: block;
      width: 200px;
      height: 200px;
      background: url('../images/404.svg');
      margin: 10px auto 30px;
    }
    .button{
      font-size: 16px;
      background: #0dd149;
      padding: 10px 15px;
      border-radius: 3px;
      display: block;
      margin: 30px auto 0;
      text-decoration: none;
      color:#fff;
      font-family: 'Arial';
      text-align: center;
      max-width: 130px;
    }
  </style>
  <body>
    <div class="error_404">
       <i class="img"></i>
       Страница не найдена!
       <a href="/" class="button">На главную</a>
    </div>
  </body>
</html>
