<?php
require_once "function.php";

if( !empty($_SESSION['id']) != '') {
    $dev = true;
    $sqlDev = '';
} else{
    $dev = false;
    $sqlDev = 'WHERE `dev` IS NULL';
}

$com = $PDO->query("SELECT *, 
                             (select count(*) from `review` where `id_com` = company.id ) as `sort`, 
                             if(`position`, `position`, 9999) as 'pos' 
                          FROM 
                            `company` $sqlDev
                          ORDER BY `pos` ASC, `sort` DESC");
$exploded = explode('.', $_SERVER['HTTP_HOST']);
$sub = array_shift($exploded);

$set = setting($PDO);
?>
<!DOCTYPE html>
<html lang="ru" dir="ltr">
<head>
	<title><?=$set['title']?></title>
	<meta name="description" content="<?=$set['description']?>">

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="x-ua-compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
	<link rel="canonical" href="https://rating-remont.moscow/"/>

	<meta name="yandex-verification" content="<?=$set['ya_code']?>" />

	<meta property="og:type" content="website" />
	<meta property="og:site_name" content="Рейтинг ремонтных компаний">
	<meta property="og:title" content="<?=$set['title']?>" />
	<meta property="og:description" content="Топ Московских компаний, выполняющих работы по ремонту квартир. Отзывы о ремонтных компаниях. Сайт создан, чтобы помочь вам найти самую надёжную организацию." />
	<meta property="og:url" content="https://rating-remont.moscow/" />
	<meta property="og:image" content="https://rating-remont.moscow/images/logo.png" />

	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="css/main.css?v=0.0.1">
	<link rel="stylesheet" href="/css/formstyler.css?v=0.0.1">
	<script src="js/jquery.js"></script>
</head>
<body>
<div class="wrapper">
    <?php mv_header($set['header']); ?>
	<div class="content">
		<div class="content__item-left">
			<div class="snippet">
				<h1><?=$set['h1']?></h1>
				<p class="index__text"><?=$set['index_text']?> <?=$dev?"":"";?></p>
			</div>
			<div class="snippet">
				<div class="snippet__list">
					<div class="string">
						<span>№</span>
						<span>Логотип</span>
						<span>Компания</span>
						<span>Голосовать</span>
						<span>Отзывов</span>
						<span>Заявки</span>
					</div>
                    <?php
                    $i = 1;
                    while ($row = $com->fetch()) {
                        $id = $row['id'];
                        $key = $func->hash($id);

                        $positiveReviewQuery = $PDO->query(
                            "SELECT
                                                    count(*) as 'pos',
                                                    (SELECT count(*) FROM `review` WHERE `id_com` = $id and `rev`= 2 and `moderation` = 1) as 'neg'
                                                FROM
                                                  `review`
                                                WHERE
                                                  `id_com` = $id and `rev`= 1 and `moderation` = 1"
                        );
                        $positiveReview = $positiveReviewQuery->fetch();
                        ?>
						<div class="string">
							<a href="/otzyvy-<?=$row['url']?>/" class="string__namber"><?=$i?></a>
							<a href="/otzyvy-<?=$row['url']?>/" class="string__img"><img src="<?=$row['logo']?>" alt="<?=$row['name']?>"/></a>
							<span class="string__name"><a href="/otzyvy-<?=$row['url']?>/" title="Информация о компании"><?=$row['name']?></a></span>
							<span class="string__like">
                      <a href="/otzyvy-<?=$row['url']?>/1/positive#rew_block" title="Только положительные отзывы"><span class="positive"><i></i><?=$positiveReview['pos']?></span></a>
                      <a href="/otzyvy-<?=$row['url']?>/1/negative#rew_block" title="Только отрицательные отзывы"><span class="negativ"><i></i><?=$positiveReview['neg']?></span></a>
                    </span>
							<a href="/otzyvy-<?=$row['url']?>/#rew_block" title="Читать все отзывы">
								<span class="string__total"><i></i><?=trim($positiveReview['pos'] + $positiveReview['neg'])?></span>
							</a>
							<span class="string__req">
                      <a href="#" class="request_modal" data-id="<?=$id?>" data-key="<?=$key?>" data-type="request" data-val="rating" title="Оставить заявку"></a>
                    </span>
						</div>
                        <?php
                        $i++;
                    }
                    ?>
				</div>
			</div>
			<div class="snippet snippet-title">
				<h3>Последние отзывы о компаниях</h3>
			</div>
			<div class="last-review">
                <?php $query = $PDO->query("SELECT * FROM `review` WHERE `moderation` = 1 and `text` != '' ORDER BY data DESC LIMIT 4");
                while($row = $query->fetch()){

                    $idCom = $row['id_com'];
                    $nameCompany = $PDO->query("SELECT `name`, `url` FROM `company` WHERE `id` = $idCom LIMIT 1");
                    $name = $nameCompany->fetch();

                    ?>
					<div class="last-review__item">
						<div class="last-review__header">
							<span><b><?=$row['fio']?></b> об <a href="/otzyvy-<?=$name['url']?>#rew_block">"<?=$name['name']?>"</a></span>
						</div>
						<div class="last-review__body"><?=$func->crop($row['text'], 350);?></div>
						<div class="last-review__footer"><span><?=date('d.m.Y G:i', $row['data'])?></span><a href="/otzyvy-<?=$name['url']?>#rew_block">Читать</a></div>
					</div>
                <?php } ?>
			</div>
			<div class="snippet">
				<div class="home_articls">
					<div class="question_woman"></div>
					<div class="home_articls__text">
						<h2>Поиск ремонтной компании с помощью рейтинга</h2>
						<span>Когда встает вопрос ремонта, многие люди обращаются в ремонтные компании. У хозяев нет времени и опыта самостоятельно заниматься всеми процедурами. Но тут возникает другая проблема: как найти надежную организацию, которая сделает все аккуратно и вовремя. Тогда на помощь приходит рейтинг.</span>
						<h3>Кому поможет рейтинг?</h3>
						<span>Рейтинг спасает при выборе той или иной ремонтной компании. Благодаря ему, можно узнать расценки, уровень профессионализма сотрудников, наличие высокотехнологичного оборудования.</span>
						<span>Рейтинг помогает таким категориям населения:</span>
						<ul>
							<li>Людям с нехваткой времени на чтение отзывов и изучение официальных сайтов компаний;</li>
							<li>Идеалистам, требующим ремонт под ключ;</li>
							<li>Не желающим переплачивать лишние деньги;</li>
							<li>Ищущим честных и порядочных работников;</li>
						</ul>
						<span>Как правило, высокие строчки занимают фирмы, сотрудники которых заключают договоры, контактируют с владельцами, советуются по поводу выбора материалов и элементов декора, устанавливают приемлемые цены и просто вежливо общаются. </span>
					</div>
				</div>
				<div class="home_articls">
					<div class="home_help"></div>
					<div class="home_articls__text">
						<h3>Помощь в выборе организации</h3>
						<span>Многие пользователи рекомендуют узнавать информацию на официальных сайтах. Они считают, что наличие собственной площадки говорит о добропорядочности и весе на рынке строительных услуг. Это не совсем верно, поскольку на сайтах компания представляет себя с положительной стороны.</span>
						<span>Даже отзывам с благодарностями верить нельзя, ведь их можно купить. Поэтому идеальное решение проблемы – пообщаться с бывшими клиентами. </span>
						<span>Благодаря нашему сайту можно:</span>
						<ul>
							<li>Узнать о лучших и порядочных ремонтных компаниях в городе;</li>
							<li>Убрать из списка мошенников;</li>
							<li>Сделать выводы о профессионализме работников.</li>
						</ul>
						<span>Также неплохо самим оставлять отзывы о компании после мероприятий на объекте. Это поможет другим пользователям определиться с выбором строителей и не попасться в ловушку к недобросовестным людям.</span>
					</div>
				</div>
			</div>
			<div class="snippet">
				<div class="home_articls">
					<div class="home_articls__text">
						<h2>Польза рейтингов ремонтных компаний и советы по выбору</h2>
						<span>Рынок ремонтных услуг широк. Открывается много компаний, предлагающих различные услуги: отделку, ремонт под ключ. Однако люди часто сталкиваются с трудностями при выборе исполнителя. Хозяева квартир хотят, чтобы все было сделано быстро и качественно. Поэтому обычно они ориентируются на рейтинги организаций.</span>
						<h3>Правила пользования рейтингами</h3>
						<span>К поиску фирмы надо подходить внимательно, поскольку от этого зависят комфорт и немалая сумма денег. Чтобы обезопасить себя и домочадцев, нужно запомнить несколько важных правил:</span>
						<ul>
							<li>Просматривать тройки лидеров или первые десятки рейтингов;</li>
							<li>Читать отзывы других клиентов;</li>
							<li>Задавать вопросы;</li>
						</ul>
						<span>Соблюдение этих простых рекомендаций существенно поможет сузить круг поисков и найти хорошую компанию, которая все сделает в наилучшем виде. </span>
						<h3>Кому полезно изучать</h3>
						<span>Рейтинги – это полезная вещь. Они выручают тех, у кого не хватает времени и терпения просматривать тематические ресурсы чатов и официальные сайты компаний. Также рейтинги пригодятся людям, не желающим тратить лишнюю копейку на ремонтные процедуры.<br/>&emsp;Еще они полезны ищущим простых и порядочных сотрудников. Хамовитые строители с вредными привычками, опаздывающие со сроками, – это вечная проблема.</span>
						<h3>Почему стоит обращаться к отзывам</h3>
						<span>Некоторые люди считают, что от отзывов мало толку. Но, в большинстве случаев, рецензии выручают. Например, помогают определить, кто из работников является самым надежным в городе или по области. Кроме того, отзывы оберегают от недобросовестных компаний.<br/>&emsp;Обязательно надо оставлять рекомендации, касательно той или иной компании. Таким образом, можно помочь сотням и тысячам потребителей.</span>
					</div>
				</div>
			</div>
		</div>
        <?php include 'modules/right.php'; ?>
	</div>
    <?php include 'modules/footer.php'; ?>
</div>
<?php include 'modules/scripts.php'; ?>
</body>
</html>