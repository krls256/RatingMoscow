<?
    require_once "function.php";

    $set = setting($PDO);

    $id = explode('.', $_GET['id'])[0];
    if(!is_numeric($id)) header('Location: /404');

    if(empty($_COOKIE['articles']) or $_COOKIE['articles'] !== $id){
        $PDO->query("UPDATE `articles` SET `visit` = visit+1 WHERE `id` = $id");
        setcookie('articles', $id, time()+60*60*24);
    }

    $sql = $PDO->query("SELECT * FROM `articles` WHERE `id`= $id");
    $row = $sql->fetch();
?>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Читать статью "<?=$row['title']?>"</title>
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="description" content="<?=$row['title']?>">

    <meta name="yandex-verification" content="<?=$set['ya_code']?>" />

	  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/css/main.css?<?=time()?>">
    <link rel="stylesheet" href="/css/formstyler.css">
    <script src="/js/jquery.js"></script>
  </head>
  <body>
    <div class="wrapper">
      <? mv_header($set['header']); ?>
      <div class="content">
        <div class="content__item-left">
          <div class="snippet page-articl">
              <h1><?=$row['title']?></h1>
              <img src="<?=$row['file']?>" width="100%"/>
              <div class="page-articl__text">
                <?=$row['text']?>
              </div>
              <div class="page-articl__footer">
                <p><?=date('d.m.Y G:i', $row['date'])?></p>
                <span><?=$row['visit']?></span>
              </div>
          </div>
        </div>
        <? include 'modules/right.php'; ?>
      </div>
      <? include 'modules/footer.php'; ?>
    </div>
    <? include 'modules/scripts.php'; ?>
  </body>
</html>
