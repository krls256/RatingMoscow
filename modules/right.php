<div class="content__item-right">
  <div class="snippet">
    <div class="snippet__right-title">Советы по ремонту</div>
    <?php $random = rand(1,6); $rowAdvice = $PDO->query("SELECT * FROM `advice` WHERE `id`= $random")->fetch(); ?>
    <div class="snippet__right-text"><?=$rowAdvice['text']?></div>
  </div>
  <div class="snippet">
    <div class="snippet__right-title">Статьи</div>
    <div class="minArticles__item">
      <?php
        $art = $PDO->query("SELECT * FROM `articles` ORDER BY `date` DESC LIMIT 4");

        while ($rowArt = $art->fetch()) {
          $text = $func->crop($rowArt['text'], 150);
      ?>
      <div class="min-Article">
        <h4><?=$rowArt['title']?></h4>
        <p><?=$text?></p>
        <div class="min-Article__footer">
          <span><?=$rowArt['visit']?></span>
          <a href="/articles/<?=$rowArt['id']?>">Читать</a>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
  <div class="snippet banner-block">
    <h3><i class="crown"></i>ТОП компаний</h3>
		<?php
      $bannersCom = $PDO->query("SELECT *,
                                        (select count(*) from `review` where `id_com` = company.id) as `sort`,
                                        if(`position`, `position`, 9999) as 'pos'
                                      FROM `company` WHERE
                                        `dev` IS NULL
                                      ORDER BY
                                        `pos` ASC, `sort`
                                      DESC LIMIT 5");
    ?>
    <ul>
		<?php
      $i = 1;
      while($bc = $bannersCom->fetch()){ ?>
      <a href="/otzyvy-<?=$bc['url']?>/">
        <li>
          <span class="banner-block__number"><?=$i?></span>
          <span class="banner-block__logo">
            <img src="https://rating-remont.moscow/<?=$bc['logo']?>" alt="<?=$bc['name']?>"/>
          </span>
          <span class="banner-block__name"><?=$bc['name']?></span>
          <span class="banner-block__next"></span>
        </li>
      </a>
      <?php $i++; } ?>
    </ul>
    <div class="banner-like">Доверяй лучшим!</div>
  </div>
  <?php /*
  <div class="snippet">
    <? $banner = $PDO->query('SELECT `banner` FROM `setting` ORDER BY `id` DESC LIMIT 1')->fetch();?>
    <img src="/<?=$banner['banner']?>" class="baners" alt="banners">
  </div>
  */ ?>
</div>