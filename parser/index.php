<?php
  include 'phpQuery.php';
  include '../class/Mysql.php';

  $id_yell = (int) $_POST['company'];

  //Переводим дату публикации в UNIX метку
  function getDatePars($data){
    $array = preg_split('/\s+/', $data, -1, PREG_SPLIT_NO_EMPTY);

    switch ($array['1']) {
      case 'января':
        $m = '01';
        break;
      case 'февраля':
        $m = '02';
        break;
      case 'марта':
        $m = '03';
        break;
      case 'апреля':
        $m = '04';
        break;
      case 'мая':
        $m = '05';
        break;
      case 'июня':
        $m = '06';
        break;
      case 'июля':
        $m = '07';
        break;
      case 'августа':
        $m = '08';
        break;
      case 'сентября':
        $m = '09';
        break;
      case 'октября':
        $m = '10';
        break;
      case 'ноября':
        $m = '11';
        break;
      case 'декабря':
        $m = '12';
        break;
    }

    $string = $array[0].'.'.$m.'.'.$array[2].' '.$array[3];
    $time = strtotime($string);

    if($time == ''){
      $time = rand(1534587124, 1569406395);
    }
    return $time;
  }

  $arr = array();

  $test = true;
  $i = 1;
  do{
    $html = file_get_contents("https://www.yell.ru/company/reviews/?id=".$id_yell."&page=".$i."&sort=recent", true);
    $res = phpQuery::newDocument($html);

    if(str_replace(' ', '', $res->find('.reviews__item')->text()) != ''){
      foreach ($res->find('.reviews__item') as $val) {
        $pq = pq($val);

        //Собираем данные
        $text = $pq->find('.reviews__item-text')->text();

        if($pq->find('.rating__value')->text() >= 4){
          $reyting = 1;
        }else{
          $reyting = 2;
        }

        $time = getDatePars($pq->find('.reviews__item-added')->text());
        $name = $pq->find('.reviews__item-user-name')->text();
        $pars_idQuery = $pq->attr('data-id');

        $check = $PDO->query("SELECT `pars_id` FROM `review` WHERE `pars_id` = $pars_idQuery LIMIT 1");
        $id_company = $PDO->query("SELECT `id`, `yell` FROM `company` WHERE `yell` = $id_yell LIMIT 1")->fetch();

        if($check->rowCount() == 0){
          $params = array(
            'fio'         => trim($name),
            'email'       => 'mail@mail.com',
            'rev'         => $reyting,
            'service'     => '0',
            'discription' => trim($text),
            'type'        => rand(1,4),
            'id_com'      => $id_company['id'],
            'data'        => $time,
            'pars_id'     => $pars_idQuery,
            'view'        => 'yell'
          );

          try{
            $query = $PDO->prepare("INSERT INTO `review`(`fio`, `email`, `rev`, `service`, `text`, `type`, `moderation`, `id_com`, `data`, `pars_id`, `view`) VALUES (:fio, :email, :rev, :service, :discription, :type, 0, :id_com, :data, :pars_id, :view)");
            $query->execute($params);
          }catch(PDOException $e){
            exit('Произошла ошыбка: '.$e->getMassage());
          }
        }
      }
    }else{
      $test = false;
    }
    $i++;
  }while ($test);

  if($query){
    echo 'ok';
  }

