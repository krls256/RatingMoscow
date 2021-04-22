<?php
include_once __DIR__ . '/../config.php';
include_once ROOT_DIR . '/class/Mysql.php';
include_once ROOT_DIR . '/parser/phpQuery.php';

//Запрос обязательно должен быть ajax-ом
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    exit('notAjax');
}

$flamp_id = (int) $_POST['company'];

function get_html($url){
  $header = array(
    'host: flamp.reviews',
    'Authorization: Bearer 2b93f266f6a4df2bb7a196bb76dca60181ea3b37',
    'Referer: https://flamp.reviews/',
    'Accept: ;q=1;depth=1;scopes={"user":{"fields":"id,name,url,image,reviews_count,sex"},"official_answer":{},"photos":{}};application/json',
  );

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Mobile Safari/537.36");
  curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

  $res = curl_exec($curl);
	curl_close($curl);

  return $res;
}

$next_link  = "https://flamp.reviews/api/2.0/filials/" . $flamp_id . "/reviews?limit=50"; //Стартуем с последних коментов
$id_company = $PDO->query("SELECT `id`, `flamp` FROM `company` WHERE `flamp` = $flamp_id LIMIT 1")->fetch(); //Получаем ID компании по flamp id
$chek_pars = true;

while ( $next_link ) 
{
  $html = get_html($next_link);
  $data = json_decode($html, true);
  if ( $data['status'] != 'success') {
    exit('Ошибка!');
  } 

  //Если запрос нормальный то вытягиваем отзывы.
  $next_link  = isset($data['next_link']) ? $data['next_link'] : false; //Ссылка на следующие 50 отзывов

  //перебераем Отзывы
  foreach($data['reviews'] as $row){
    //перед тем как добавлять отзыв нужно проверить был ли он уже спарсин или нет
    $review_id = $row['id'];
    $check     = $PDO->query("SELECT `pars_id` FROM `review` WHERE `pars_id` = $review_id LIMIT 1");

    if($check->rowCount() == 0){
      //Положительный или отрицательный отзыв
      if($row['rating'] >= 4){
        $mood = 1;
      }else{
        $mood = 2;
      }

      //Собераем данные о пользователе
      $params = array(
        'fio'         => trim($row['user']['name']),
        'email'       => 'mail@mail.com',
        'rev'         => $mood,
        'service'     => '0',
        'discription' => trim($row['text']),
        'type'        => rand(1,4),
        'id_com'      => $id_company['id'],
        'data'        => strtotime($row['date_created']),
        'pars_id'     => $review_id,
        'view'        => 'flamp'
      );
      try{
        $query = $PDO->prepare("INSERT INTO `review`(`fio`, `email`, `rev`, `service`, `text`, `type`, `moderation`, `id_com`, `data`, `pars_id`,`view`) VALUES (:fio, :email, :rev, :service, :discription, :type, 0, :id_com, :data, :pars_id, :view)");
        $query->execute($params);
      }catch(PDOException $e){
        $chek_pars = false;
      }
    }
  }
}

if($chek_pars){
  echo 'ok';
}else{
  echo 'Ошибка!';
}
?>
