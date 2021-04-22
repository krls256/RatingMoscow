<?php

session_start();

//Проверяем авторизацию пользователя
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
  if(empty($_SESSION['id']) && $_SESSION['id'] == '' && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'){
    header('Location: /admin/login');
  }
}
require_once "../class/Mysql.php"; //Подключаем базу данных

//Типы ремонта
function typeRemont($a){
  switch ($a) {
    case '1':
       $type = 'Косметический ремонт квартиры';
      break;
    case '2':
       $type = 'Капитальный ремонт квартиры';
      break;
    case '3':
       $type = 'Элитный ремонт квартир';
      break;
    default:
      $type = 'Дизайн проект квартиры';
      break;
  }

  return $type;
}

function setting(\PDO $PDO) {
    $exploded = explode('.', $_SERVER['HTTP_HOST']);
  $sub = array_shift($exploded);

  if ($sub =='rating-remont')
    $domen = 'index';
  else
    $domen = $sub;

  $settingQuery = $PDO->prepare("SELECT * FROM `setting` WHERE `name` = ? LIMIT 1");
  $settingQuery->execute(array($domen));

  $set = $settingQuery->fetch();

  return $set;
} 

function translit($s) {
  $s = (string) $s; // преобразуем в строковое значение
  $s = strip_tags($s); // убираем HTML-теги
  $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
  $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
  $s = trim($s); // убираем пробелы в начале и конце строки
  $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
  $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
  $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
  $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
  return $s; // возвращаем результат
}

//Информация о компании
function infoCompany($id, $PDO){
  $companyNameQuery = $PDO->prepare("SELECT `id`,`name` FROM `company` WHERE `id` = ? LIMIT 1");
  $companyNameQuery->execute(array($id));

  return  $companyNameQuery->fetch(PDO::FETCH_LAZY);
}

//Авторизация
if(isset($_GET['func']) == 'login' and empty($_SESSION['id'])){
  $login = trim($_POST['login']);

  $query = $PDO->prepare("SELECT `id` ,`login`, `pass` FROM `admin` WHERE `login` = ? LIMIT 1");
  $query->execute(array($login));

  //проверяем наличие пользователя
  if($query->rowCount() > 0){
    while($row = $query->fetch(PDO::FETCH_LAZY)){

      //Проверяем пароль
      if(hash_equals($row->pass, crypt($_POST['password'], $row->pass))){
        $_SESSION['id'] = $row->id;
        echo 'ok';
      }else
        echo 'pass';
    }
  }else{
    echo  'user';
  }
}

function array_key_last( $array ) {

  if (!is_array($array) || empty($array)) {
      return NULL;
  } else {
    return array_keys($array)[count($array)-1];
  }
  
}

if(($_GET['func'] ?? '') == 'edit_setting' and ($_SESSION['id'] != '')){
  $city   = setting($PDO)['id'];  // ID города
  $data   = $_POST;               // Полученые данные
  $keys   = '';                   // Подготавливаем поля для записи
  $query  = [];                   // Подготавливаем параметры
  $error  = [];                   // Какие строки нужно заполнить
  $res    = true;

  //перебераем данные
  foreach ($data as $key => $value) {
    $coma = $key != array_key_last($data)?', ':''; //Ставим точки кроме последнего параметра

    //Проверяем все поял кроме метрик
    if( $value == '' and ($key != 'google_metriks' and $key != 'ya_metriks')) {
      array_push($error, $key);

      $res = false;
    }

    $keys .= "`".$key."` = ?" . $coma;
    array_push($query, $value);
  }

  if ( $res ){ 
    //Записываем в базу
    try{
      $up = $PDO->prepare("UPDATE `setting` SET $keys WHERE `id` = $city LIMIT 1");
      $up->execute($query);
      
      $res = 'ok';
    }catch(PDOException $e){
      $res = 'error';
    }
  } else {
    $res = 'error';
  }

  $json = ['res'=>$res, 'error'=>$error];

  header('Content-Type: application/json');
  echo json_encode($json);
}


if(($_GET['func'] ?? '') == 'edit_banner' and ($_SESSION['id'] != '')){
  $size = getimagesize($_FILES['file']['tmp_name']);

  //Загрузка логотипа
  if($_FILES['file'] != '' && ($size[0] == 300) && ($size[1] == 400)){
    $params = array();
    $upload__dir  = "../upload/banners/";
    $upload__file = $upload__dir . $_FILES['file']['name'];

    //Если файл загружен не коректно
    if(move_uploaded_file($_FILES['file']['tmp_name'], $upload__file))
      $params['banner'] = $upload__file;
    else
      exit('file_error');

  }else{
    exit('file_size');
  }

  if($params['banner'] != ''){
    $sqlBanner = $PDO->prepare('INSERT INTO `setting`(`banner`) VALUES (?)');
    $sqlBanner->execute(array($params['banner']));
  }
}

//Добавление компании
if(($_GET['func'] ?? '') == 'create-company' and ($_SESSION['id'] != '')){

  //Подготавливаем массив двнных для добовления в базу
  $params = array(
                'name'        =>  trim( $_POST['name'] ),
                'data'        =>  strtotime( $_POST['data'] ),
                'inn'         =>  ( int ) $_POST['inn'],
                'phone'       =>  trim( $_POST['phone'] ),
                'sity'        =>  trim( $_POST['sity'] ),
                'email'       =>  trim( $_POST['email'] ),
                'city'        =>  ( int ) $_POST['city'],
                'address'     =>  trim( $_POST['address'] ),
                'map'         =>  trim( $_POST['map'] ),
                'fb'          =>  trim( $_POST['fb'] ),
                'ok'          =>  trim( $_POST['ok'] ),
                'vk'          =>  trim( $_POST['vk'] ),
                'wa'          =>  trim( $_POST['wa'] ),
                'vb'          =>  trim( $_POST['vb'] ),
                'tg'          =>  trim( $_POST['tg'] ),
                'tw'          =>  trim( $_POST['tw'] ),
                'ins'         =>  trim( $_POST['ins'] ),
                'yb'          =>  trim( $_POST['yb'] ),
                'description' =>  trim( $_POST['description'] ),
                'url'         =>  translit( $_POST['name'] ),
                'yell'        =>  ( int ) $_POST['yell'],
                'flamp'       =>  ( int ) $_POST['flamp'],
            );

  $size = getimagesize($_FILES['file']['tmp_name']);

  //проверяемм ввеленные данные на заполнение полей
  if(($params['name'] or $params['data'] or $params['phone'] or $params['sity'] or $params['email'] or $params['city'] or $param['address'] or $params['map'] or $params['description']) == ''){
    exit('input_error');
  }

  if ( $_POST['davCompany'] ?? null ) {
    $params['dev'] = true;
  } else {
    $params['dev'] = null;
  }

  //Проверяем подвязан ли id yell
  if ( $params['yell'] ) {
    $yellQuery = $PDO->prepare("SELECT count(*) as `count` FROM  `company` WHERE `yell` =  ? and `yell` IS NOT NULL");
    $yellQuery->execute(array($params['yell']));
    $yellGet = $yellQuery->fetch();

    if( $yellGet['count'] >= 1){
      exit('yell_error');
    }
  } else {
    $params['yell'] = null;
  }

  //Проверяем подвязан ли id flamp
  if ( $params['flamp'] ) {
    $flampQuery = $PDO->prepare("SELECT count(*) as `count` FROM  `company` WHERE `flamp` =  ? and `yell` IS NOT NULL");
    $flampQuery->execute([$params['flamp']]);
    $flampGet = $flampQuery->fetch();

    if($flampGet['count'] >= 1){
      exit('flamp_error');
    }
  } else {
    $params['flamp'] = null;
  }

  //Загрузка логотипа
  if($_FILES['file'] != '' and ($_FILES['file']['size'] < 2097152) && ($size[0] <= 100) && ($size[1] <= 100)){

    /*
    @ Воизюежании копироания файлов, мы меняем название на свое.
    @ В переменно ext мы сохраняем расщиренние файла, в
    @ переменной upload__file собираем новое имя состояще из:
    @ префикс + время + . + расширение.
    */
    $upload__dir  = "../upload/";
    $ext = explode('.', $_FILES['file']['name'])[1];
    $upload__file = $upload__dir . 'remont_' . time() .'.'. $ext;

    //Если файл загружен не коректно
    if(move_uploaded_file($_FILES['file']['tmp_name'], $upload__file))
      $params['logo'] = $upload__file;
    else
      exit('file_error');

  }else{
    exit('file_size');
  }

  //Выполняем запрос к базе
  $stmt = $PDO->prepare("INSERT INTO `company`(`name`, `data`, `inn`, `phone`, `sity`, `email`, `city`, `address`, `map`, `fb`, `ok`, `vk`, `wa`, `vb`, `tg`, `tw`, `ins`, `yb`, `description`, `logo`, `url`, `yell`, `flamp`, `dev`) VALUES (:name, :data, :inn, :phone, :sity, :email, :city, :address, :map, :fb, :ok, :vk, :wa, :vb, :tg, :tw, :ins, :yb, :description, :logo, :url, :yell, :flamp, :dev)");
  $stmt->execute($params);

  if($stmt)
    echo 'ok';
  }

//Одобрить отзыв
if(($_GET['func'] ?? '') == 'review-good' and ($_SESSION['id'] != '')){

  //проверям подмену id
  if(substr(md5($_POST['id']), 0, 8) !=  $_POST['key']){
    exit('Упс... Что-то пошло не так ):');
  }

  try{
    $query = $PDO->prepare('UPDATE `review` SET `moderation`= ? WHERE `id` = ?');
    $query->execute(array(1, (int) $_POST['id']));

    if($query)
      echo 'ok';
  }catch(PDOException $e){
    exit('Ошибка' . $e->getMessage());
  }
}

//Удаляем отзывы
if(($_GET['func'] ?? '') == 'review-del' and ($_SESSION['id'] != '')){
  $id = (int) $_POST['id'];

  //проверям подмену id
  if(substr(md5($_POST['id']), 0, 8) !=  $_POST['key']){
    exit('Упс... Что-то пошло не так ):');
  }

  try{
    $query = $PDO->query("DELETE FROM `review` WHERE `id` = $id");

    if($query)
      echo 'ok';
  }catch(PDOException $e){
    exit('Ошибка' . $e->getMessage());
  }
}

//Одобрить комментарий
if(($_GET['func'] ?? '') == 'comment-good' and ($_SESSION['id'] != '')){

  //проверям подмену id
  if(substr(md5($_POST['id']), 0, 8) !=  $_POST['key']){
    exit('Упс... Что-то пошло не так ):');
  }

  try{
    $query = $PDO->prepare('UPDATE `comment` SET `moderation`= ? WHERE `id` = ?');
    $query->execute(array(1, (int) $_POST['id']));

    if($query)
      echo 'ok';
  }catch(PDOException $e){
    exit('Ошибка' . $e->getMessage());
  }
}

//Удаляем Комментарий
if(($_GET['func'] ?? '') == 'comment-del' and ($_SESSION['id'] != '')){
  $id = (int) $_POST['id'];

  //проверям подмену id
  if(substr(md5($_POST['id']), 0, 8) !=  $_POST['key']){
    exit('Упс... Что-то пошло не так ):');
  }

  try{
    $query = $PDO->query("DELETE FROM `comment` WHERE `id` = $id");

    if($query)
      echo 'ok';
  }catch(PDOException $e){
    exit('Ошибка' . $e->getMessage());
  }
}

if(($_GET['func'] ?? '') == 'create-artical' and ($_SESSION['id'] != '')){

  if($_POST['title'] == '' or $_POST['text'] == '' or $_POST['autor'] == ''){
    exit('text_error');
  }

  $params = array(
    'title' => $_POST['title'],
    'txt'   => $_POST['text'],
    'autor' => $_POST['autor'],
    'data'  => time(),
    'visit' => rand(76, 238)
  );

  if($_FILES['file'] != '' and ($_FILES['file']['size'] < 5242880)){

    //Усли файл существует то загружаем его
    $upload__dir  = "../upload/articles/";
    $ext = explode('.', $_FILES['file']['name'])[1];
    $upload__file = $upload__dir . 'articles_' . time() .'.'. $ext;

    //Если файл загружен не коректно
    if(move_uploaded_file($_FILES['file']['tmp_name'], $upload__file))
      $params['file'] = $upload__file;
    else
      exit('file_error');

  }else{
    exit('file_error');
  }

  try{
    $query = $PDO->prepare("INSERT INTO `articles`(`title`, `text`, `autor`, `date`, `file`, `visit`) VALUES (:title, :txt, :autor, :data, :file, :visit)");
    $query->execute($params);

    if($query)
      echo 'ok';
  }catch(PDOException $e){
    exit('sql_error');
  }
}

if(($_GET['func'] ?? '') == 'del_company' and ($_SESSION['id'] != '')){
  $id = (int) $_POST['id'];

  //проверям подмену id
  if(substr(md5($_POST['id']), 0, 8) !=  $_POST['key']){
    exit('Упс... Что-то пошло не так ):');
  }

  try{
    $query = $PDO->prepare("DELETE FROM `company` WHERE `id`= ?");
    $query->execute(array($id));

    if($query)
      echo 'ok';
  }catch(PDOException $e){
    exit('Ошибка' . $e->getMessage());
  }
}

//Редактирование отзывов
if(($_GET['func'] ?? '') == 'review_edit' and ($_SESSION['id'] != '')){
 if(!is_numeric($_POST['id'])){
   exit('Что-то пошло не так...');
 }
 $param = array(
   'id'         => $_POST['id'],
   'fio'        => trim($_POST['fio']),
   'email'      => trim($_POST['email']),
   'review'     => trim($_POST['review']),
   'type'       => (int) $_POST['type'],
   'service'    => trim($_POST['service']),
   'pos'        => (int) $_POST['pos'],
   'data'       => (int) strtotime($_POST['data']),
   'moderation' => (int) $_POST['method']
 );

 if($param['data'] == 0){
   exit('Не верно указан формат даты.');
 }

 foreach ($param as $key => $value) {
   if($value === ''){
     exit('Заполните все поля.');
   }
 }

 try{
   $sql = $PDO->prepare("UPDATE `review` SET `fio`= :fio, `email` = :email, `text` = :review, `type` = :type, `service` = :service, `pos`= :pos, `data` = :data, `moderation` = :moderation WHERE `id` = :id");
   $ret = $sql->execute($param);
 }catch(PDOException $e){
   exit($e->getMessage());
 }

  if($ret)
    echo 'ok';
}

if(($_GET['func'] ?? '') == 'review-hr-edit' and ($_SESSION['id'] != '')){
  if( !is_numeric($_POST['id']) )
    exit('Что-то пошло не так...');

  $params = [
    'position'  => $_POST['position'],
    'text'      => htmlspecialchars($_POST['review']),
    'data'      => strtotime($_POST['data'])
  ];

  try { 
    $sql = $PDO->prepare("UPDATE `review_hr` SET position = :position, text = :text, data = :data");
    $ret = $sql->execute($params);
  } catch (PDOException $e) { 
    exit('Ошибка: ' . $e->getMessage()); 
  }

  if ($ret) 
    echo 'ok'; 
}

if(($_GET['func'] ?? '') == 'review-hr-del' and ($_SESSION['id'] != '')) {
  if( !is_numeric($_POST['id']) )
    exit('Что-то пошло не так...');

  $sql = $PDO->prepare("DELETE FROM `review_hr` WHERE `id`= ?");
  $ret = $sql->execute(array($_POST['id']));

  if ( $ret ) 
    echo 'ok';
}

if(($_GET['func'] ?? '') == 'edit_company' and ($_SESSION['id'] != '')){

    if(substr(md5($_POST['id']), 0, 8) != $_POST['hash']){
      exit('fatal');
    }

    //Подготавливаем массив двнных для добовления в базу
    $params = array(
                  'name'            =>  trim( $_POST['name'] ),
                  'data'            =>  strtotime( $_POST['data'] ),
                  'inn'             =>  (int) $_POST['inn'],
                  'phone'           =>  trim( $_POST['phone'] ),
                  'sity'            =>  trim( $_POST['sity'] ),
                  'email'           =>  trim( $_POST['email'] ),
                  'city'            =>  (int) $_POST['city'],
                  'position'        =>  (int) $_POST['position'],
                  'address'         =>  trim( $_POST['address']),
                  'map'             =>  trim( $_POST['map'] ),
                  'fb'              =>  trim( $_POST['fb'] ),
                  'ok'              =>  trim( $_POST['ok'] ),
                  'vk'              =>  trim( $_POST['vk'] ),
                  'wa'              =>  trim( $_POST['wa'] ),
                  'vb'              =>  trim( $_POST['vb'] ),
                  'tg'              =>  trim( $_POST['tg'] ),
                  'tw'              =>  trim( $_POST['tw'] ),
                  'ins'             =>  trim( $_POST['ins'] ),
                  'yb'              =>  trim( $_POST['yb'] ),
                  'description'     =>  trim( $_POST['description'] ),
                  'descriptionhr'   =>  trim( $_POST['description_hr'] ),
                  'id'              =>  ( int ) $_POST['id'],
                  'url'             =>  translit( $_POST['name'] ),
                  'yell'            =>  ( int ) $_POST['yell'],
                  'flamp'           =>  ( int ) $_POST['flamp']
              );

    //проверяемм ввеленные данные на заполнение полей
    if(($params['name'] or $params['data'] or $params['phone'] or $params['sity'] or $params['email'] or $params['city'] or $params['position'] or $param['address'] or $params['map'] or $params['description']) == ''){
      exit('input_error');
    }

    if ( !empty( $_POST['davCompany'] ) ) {
      $params['dev'] = true;
    } else {
      $params['dev'] = null;
    }

    //Проверяем подвязан ли id yell
    if ( $params['yell'] ) {
      $yellQuery = $PDO->prepare("SELECT count(*) as `count` FROM  `company` WHERE `yell` =  ? and `yell` IS NOT NULL and `id` != ?");
      $yellQuery->execute(array($params['yell'], $params['id']));
      $yellGet = $yellQuery->fetch();

      if( $yellGet['count'] >= 1){
        exit('yell_error');
      }
    } else {
      $params['yell'] = null;
    }

      //Загрузка логотипа
    if($_FILES['file']['error'] != '4'){
      if( ($_FILES['file']['size'] < 2097152) && ($size[0] <= 100) && ($size[1] <= 100)){

        /*
        @ Воизюежании копироания файлов, мы меняем название на свое.
        @ В переменно ext мы сохраняем расщиренние файла, в
        @ переменной upload__file собираем новое имя на состояще из:
        @ префикс + время + . + расширение.
        */
        $upload__dir  = "../upload/";
        $ext = explode('.', $_FILES['file']['name'])[1];
        $upload__file = $upload__dir . 'remont_' . time() .'.'. $ext;

        //Если файл загружен не коректно
        if(move_uploaded_file($_FILES['file']['tmp_name'], $upload__file))
          $params['logo'] = $upload__file;
        else
          exit('file_error');

      }else{
        exit('file_size');
      }
    }else{
      $params['logo'] = NULL;
    }

    //Выполняем запрос к базе
    $stmt = $PDO->prepare("UPDATE `company` SET `name`=:name, `phone`=:phone, `city`= :city, `address`=:address, `sity`=:sity, `description`=:description, `description_hr`=:descriptionhr ,`logo`=COALESCE(:logo , `logo`), `map`=:map, `data`=:data, `email`=:email, `position`=:position, `fb`=:fb, `vk`=:vk, `tw`=:tw, `wa`=:wa, `vb`=:vb, `ok`=:ok, `tg`=:tg, `ins`=:ins, `inn`=:inn, `yb`=:yb, `url`=:url, `yell`=:yell, `flamp`=:flamp, `dev`=:dev WHERE `id`=:id");
    $stmt->execute($params);

    if($stmt)
      echo 'ok';
}

if(($_GET['func'] ?? '') == 'edit_article' and ($_SESSION['id'] != '')){

  if(substr(md5($_POST['id']), 0, 8) != $_POST['key']){
    exit('fatal');
  }

  $params = array(
    'id'    => (int) $_POST['id'],
    'title' => trim($_POST['title']),
    'txt'   => $_POST['text'],
    'autor' => trim($_POST['autor']),
  );

  //Загрузка логотипа
  if($_FILES['file']['error'] != '4'){
    if( ($_FILES['file']['size'] < 2097152) && ($size[0] <= 100) && ($size[1] <= 100)){

    /*
    @ Воизюежании копироания файлов, мы меняем название на свое.
    @ В переменно ext мы сохраняем расщиренние файла, в
    @ переменной upload__file собираем новое имя на состояще из:
    @ префикс + время + . + расширение.
    */
    $upload__dir  = "../upload/articles/";
    $ext = explode('.', $_FILES['file']['name'])[1];
    $upload__file = $upload__dir . 'articles_' . time() .'.'. $ext;

    //Если файл загружен не коректно
    if(move_uploaded_file($_FILES['file']['tmp_name'], $upload__file))
      $params['file'] = $upload__file;
    else
      exit('Ошибка загрузки файла.');

    }else{
      exit('Файл слишком большой!');
    }
  }else{
    $params['file'] = NULL;
  }

  //Выполняем запрос к базе
  $stmt = $PDO->prepare("UPDATE `articles` SET `title`=:title, `text`=:txt, `autor`=:autor, `file` = COALESCE(:file , `file`) WHERE `id`=:id ");
  $stmt->execute($params);

  if($stmt)
    echo true;
}

if(($_GET['func'] ?? '') == 'advice' and ($_SESSION['id'] != '')){
  for($i=1; $i<8;$i++){
    $adviceName = $_POST["advice-" . $i];
    $QueryUpdateAdvice = $PDO->prepare("UPDATE `advice` SET `text` = ? WHERE `id` = ?");
    $QueryUpdateAdvice->execute(array($adviceName, $i));
  }

  echo 'ok';
}
