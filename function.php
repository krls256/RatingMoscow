<?php

require_once "config.php";
  ini_set("session.cookie_samesite", 'Strict');
  require  "class/Mysql.php";


class Func{

    //Обрезка строк
    public function crop($string ,$long){
      $text = strip_tags($string);
      $text = substr($text, 0, $long);
      $text = rtrim($text, "!,.-");
      $text = substr($text, 0, strrpos($text, ' ')).'...';

      return $text;
    }

    public function hash($id){
      return substr(md5($id), 0, 8);
    }

    public function typeRepairs($number){
      switch ($number) {
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

    //Удаляем разширение php
    public function urlClear($POST){
      if($POST != ''){
        return explode('.', $POST)[0];
      }
    }

}

$func = new Func($PDO);

function setting(\PDO $PDO) {
  $exploded = explode('.', $_SERVER['HTTP_HOST']);
  $sub = array_shift($exploded);
  // $sub == 'rating-moscow' - for local
  if ($sub =='rating-remont' || $sub == 'rating-moscow')
    $domen = 'index';
  else
    $domen = $sub;

  $settingQuery = $PDO->prepare("SELECT * FROM `setting` WHERE `name` = ? LIMIT 1");
  $settingQuery->execute(array($domen));

  $set = $settingQuery->fetch();

  return $set;
} 

function mv_header($header){
  include 'modules/header.php';
}

function captcha($id, $key_id, $captcha){
  include "class/recaptchalib.php";

  $key = substr(md5($id), 0, 8);
  if( $id == '' or $key_id != $key){
    echo 'Упс... Что-то пошло не так.';
    exit();
  }

  // ваш секретный ключ
  $secret     = "6LcrULwUAAAAAHFi6HnHODyol4wiqudoKVLdjPXn";
  $reCaptcha  = new ReCaptcha($secret);
  $response   = null;
  $chek       = true;

  if ($captcha) {
    $response = $reCaptcha->verifyResponse(
                  $_SERVER["REMOTE_ADDR"],
                  $captcha
                );
  }

  if ($response == null && !$response->success) {
    $chek = false;
  }

  return $chek;
}

function multiexplode ($delimiters,$string) {

    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}

function get_review($PDO, $post, $show, $list, $page, $ID){
  $resultReview =[]; //Тут будут готовый результат 

  $reviewQuery = $PDO->prepare("SELECT * FROM `review` WHERE `id_com`= ? and `moderation` = ? and `text` != '' $post ORDER BY `data` DESC LIMIT $show OFFSET $list");
  $reviewQuery->execute(array($ID, 1));
  
  //Забрасываем полученые отзывы в массив
  while($push = $reviewQuery->fetch(PDO::FETCH_ASSOC)){
    array_push($resultReview, $push);
  }

  //Делаем закрепы
  if(empty($post) and $page+1==1){
    $fixing = $PDO->prepare("SELECT * FROM `review` WHERE `id_com`= ? and `moderation` = ? and `text` != '' and `pos` > 0");
    $fixing->execute(array($ID, 1));
    
    while($fetchFixing = $fixing->fetch()){
      $index = $fetchFixing['pos']-1;
      $resultReview[$index] = $fetchFixing;
    }
  }

  return $resultReview;
}

function get_review_hr($PDO, $post, $show, $list, $page, $ID){
  $resultReview =[]; //Тут будут готовый результат 

  $reviewQuery = $PDO->prepare("SELECT * FROM `review_hr` WHERE `id_com`= ? and `text` != '' $post ORDER BY `data` DESC LIMIT $show OFFSET $list");
  $reviewQuery->execute(array($ID));
  
  //Забрасываем полученые отзывы в массив
  while($push = $reviewQuery->fetch(PDO::FETCH_ASSOC)){
    array_push($resultReview, $push);
  }

  return $resultReview;
}

function count_review_hr($PDO, $post, $id){
  $reviewQuery = $PDO->prepare("SELECT count(*) FROM `review_hr` WHERE `id_com`= ? and `text` != '' $post");
  $reviewQuery->execute(array($id));

  return $reviewQuery->fetch()[0];
}

function count_review($PDO, $post, $id){
  $reviewQuery = $PDO->prepare("SELECT count(*) FROM `review` WHERE `id_com`= ? and `moderation` = ? and `text` != '' $post");
  $reviewQuery->execute(array($id, 1));

  return $reviewQuery->fetch()[0];
}

function error404(){
  http_response_code(404);
  include '404.php';
  exit();
}

function errorClose(){
  http_response_code(403);
  include 'close.html';
  exit();
}
?>
