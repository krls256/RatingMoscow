<?php
  include "function.php";

# Пользовательские отзывы
if($_GET['func'] == 'new_review'){
  if( !captcha($_POST['id'], $_POST['key'], $_POST["g-recaptcha-response"]))
    echo 'Капча не пройдена.';

  $req = array(
    'fio'     => trim($_POST['fio']),
    'email'   => trim($_POST['email']),
    'rev'     => (int) $_POST['like'],
    'service' => trim($_POST['NZ']),
    'type'    => (int) $_POST['type'],
    'id_com'  => (int) $_POST['id'],
    'data'    => time(),
    'view'    => 'user'
  );

  if(!filter_var($req['email'], FILTER_VALIDATE_EMAIL))
    exit('Email указан не верно.');

  # Проверяем заполнение полей
  if($req['fio'] == '' or $req['email'] == '' or $req['like'] or $req['type'] == '' or $req['id_com'] == '')
    exit('Пожалуйста заполните все поля.');

  $req['discription'] = trim($_POST['review']);

  try{
    $query = $PDO->prepare("INSERT INTO `review`(`fio`, `email`, `rev`, `service`, `text`, `type`, `moderation`, `id_com`, `data`, `view`) VALUES (:fio, :email, :rev, :service, :discription, :type, 0, :id_com, :data, :view)");
    $ret = $query->execute($req);
  }catch(PDOException $e){
    exit('Ошибка: ' . $e->getMessage());
  }

  if($ret) echo 'ok';
}

# Публикация отзывов HR
if($_GET['func'] == 'new_reviews_hr')
{
  # Проверяем капчу
  if(!captcha($_POST['id'], $_POST['key'], $_POST["g-recaptcha-response"]))
    exit('Капча не пройдена.');

  # Проверяем есть ли в запросе тип отзыва
  if ($_POST['review-positiv'] == '' or $_POST['review-neg'] == '')
    exit('Пожалуйста оставте свой отзыв.');

  $text = "<p><strong>Плюсы: </strong>" . trim($_POST['review-positiv']) . "</p><p><strong>Минусы: </strong>" . trim($_POST['review-neg']) . "</p>";

  $req = [
    'position'    => trim($_POST['position']),
    'discription' => htmlspecialchars(addslashes($text)),
    'rev'         => (int) $_POST['like'],
    'date'        => time(),
    'id_com'      => (int) $_POST['id'],
  ];

  # Проверяем пустые поля
  if($req['position'] == '' or $req['discription'] == '' or $req['like'])
    exit('Пожалуйста заполните все поля.');

  try{
    $query  = $PDO->prepare("INSERT INTO `review_hr`(`position`, `rev`, `pars_id`, `text`, `id_com`, `data`) VALUES (:position, :rev, NULL, :discription, :id_com, :date)");
    $ret    = $query->execute($req);
  } catch (PDOException $e) {
    exit('Ошибка: ' . $e->getMessage());
  }

  if($ret)
    echo 'ok';
  else 
    echo 'error';
} 

# ПУбликация коменнтариев
if($_GET['func'] == 'create_comment')
{
  # Проверяем капчу
  if ( !captcha($_POST['id'], $_POST['key'], $_POST["g-recaptcha-response"]) )
    exit('Вы не указали карчу.');

  # Проверяем наличие типа
  if ( !in_array($_POST['type'], ['hr', 'rating']) )
    exit('Что-то пошло не так...');

  $req = array(
    'id'           => (int) $_POST['id'],
    'fio'          => trim($_POST['fio']),
    'discription'  => trim($_POST['comment']),
    'data'         => time(),
    'type'         => $_POST['type']
  );

  foreach ($req as $value)
    if(iconv_strlen($value) == 0) exit('Пожалуйста заполните все поля.');

  try{
    $query  = $PDO->prepare("INSERT INTO `comment`(`fio`, `text`, `review`, `moderation`, `data`, `type`) VALUES (:fio, :discription, :id, 0, :data, :type)");
    $ret    = $query->execute($req);

    if($ret) echo 'ok';
  }catch(PDOException $e){
    exit('Произошла ошибка:'.$e->getMassage());
  }
}

if($_GET['func'] == 'search'){
  $textSearch = trim($_POST['value']);

  try{
    $Qsearch= $PDO->prepare("SELECT id, title, `text`, 'ar' as db, '' as url FROM articles WHERE (title LIKE :search) or (`text` LIKE :search) UNION ALL SELECT id, name, description, 'com' as db, url as url FROM company WHERE (name LIKE :search) or (description LIKE :search)");
    $Qsearch->execute(array("search"=>"%$textSearch%"));
  }catch(PDOException $e){
    echo $e->getMassage();
  }

  if($Qsearch and $Qsearch->rowCount() > 0){
    while($row = $Qsearch->fetch()){
      $url = $row['db']=='com'?'/otzyvy-' . $row['url'] . '/':'/articles/'.$row['id'];
      $type = $row['db']=='com'?'company':'articles';

      if(mb_strlen($row['text']) > 150){
        $text = $func->crop($row['text'], 150);
      }else{
        $text = $row['text'];
      }
      
      echo '<a href="'.$url.'" class="result-search">
              <div class="result-search__item">
                <h4>'.$row['title'].'</h4>
                <span>'.$text.'</span>
              </div>
            </a>';
    }
  }else{
    echo '<div class="search__error">По вашему запросу, нечего не найдено?</div>';
  }
}

if($_GET['func'] == 'new_requests'){

  //Выкидываем хитрых
  if( !captcha($_POST['id'],  $_POST['key'], $_POST["g-recaptcha-response"]))
    exit('Вы не указали карчу.');

  if($_POST['fio'] == '' or $_POST['phone'] == '')
     exit('Пожалуйста заполните все поля.');

   $id    = $_POST['id'];
   $query = $PDO->query("SELECT `email`, `email_hr` FROM `company` WHERE `id`= $id LIMIT 1");
   $row   = $query->fetch();

  if($query->rowCount()){

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
  
    if ( $_POST['type'] == 'rating' ) {
      $email = $row['email']; //Мыло компании

      $subject = 'Новая заявка с сайта '.$_SERVER['HTTP_HOST'];
      $message = '<html>
        <head>
          <title>Новая заявка с сайта '.$_SERVER['HTTP_HOST'].'</title>
        </head>
        <body>
          <h1 style="margin-botto:20px;">Новая заявка с сайта '.$_SERVER['HTTP_HOST'].'</h1>
          <div>Пользователь сайта '.$_SERVER['HTTP_HOST'].' хочет заказать услуги вашей компании.</div>
          </br>
          <div style="margin-bottom:10px;"><b>Данные:</b></div>
          </br>
          <div><b>Ф.И.О: </b>'.$_POST['fio'].'</div>
          </br>
          <div><b>Адрес: </b>'.$_POST['address'].'</div>
          </br>
          <div><b>Телефон: </b>'.$_POST['phone'].'</div>

          <div style="margin-top: 20px;"><b>*</b> Сообщение отправлено автоматически, на него отвечать не нужно.</div>
        </body>
        </html>';

        $headers .= 'From: Новая заявка <info@rating-remont.moscow>';
    } else 
    if ( $_POST['type'] == 'hr' ) {
      $email = $row['email_hr']; //Мыло компании 
      $subject = 'Новая заявка с сайта '.$_SERVER['HTTP_HOST']. ' HR';

      $message = '<html>
        <head>
          <title>Пользователь хочет узнать о текущих вакансиях '.$_SERVER['HTTP_HOST'].'</title>
        </head>
        <body>
          <h1 style="margin-botto:20px;">Пользователь хочет узнать о текущих вакансиях '.$_SERVER['HTTP_HOST'].'</h1>
          <div>Пользователь сайта '.$_SERVER['HTTP_HOST'].' хочет узнать о вакансиях вашей компании.</div>
          </br>
          <div style="margin-bottom:10px;"><b>Данные:</b></div>
          </br>
          <div><b>Ф.И.О: </b>'.$_POST['fio'].'</div>
          </br>
          <div><b>Телефон: </b>'.$_POST['phone'].'</div>

          <div style="margin-top: 20px;"><b>*</b> Сообщение отправлено автоматически, на него отвечать не нужно.</div>
        </body>
        </html>';

      $headers .= 'From: Новая заявка <info@rating-remont.moscow> HR';
    }

    $to = $email;           // обратите внимание на запятую
   
    if(mail($to, $subject, $message,  $headers))
      echo true;
    else
      exit('Что-то пошло не так...');

  }else{
   exit('Что-то пошло не так...');
  }
}

?>
