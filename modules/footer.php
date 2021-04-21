<footer>
  <nav>
    <a href="#" class="logo">RATING REMONT</a>
    <menu class="header__menu">
      <li><a href="/">Рейтинг</a></li>
      <li><a href="/all-review">Все отзывы</a></li>
      <li><a href="/articles">Статьи</a></li>
      <li><a href="/contacts">Контакты</a></li>
    </menu>
  </nav>
  <div class="copy">При использовании материалов гиперссылка на <?=$_SERVER['HTTP_HOST']?> обязательна.<br/><span>©2019-<?=date('Y')?> Все права защищены.</span></div>
</footer>
<div class="modal">
  <div class="modal__window window__review">
    <h3>Оставить отзыв<span class="close"></span></h3>
    <div class="modal__error"></div>
    <form class="modal__item" id="review">
      <input type="hidden" name="id" value="" data-check="true"/>
      <input type="hidden" name="key" value="" data-check="true"/>
      <label>
        <span>Имя <b>*</b></span>
        <input type="text" name="fio" value="" data-check="true">
      </label>
      <label>
        <span>Отзыв</span>
        <textarea name="review"></textarea>
      </label>
      <div class="modal__like">
        <span data-like="1">Рекомендую</span>
        <span data-like="2">Не рекомендую</span>
      </div>
      <select class="modal__type" name="type">
        <option value="1">Косметический ремонт квартиры</option>
        <option value="2">Капитальный ремонт квартиры</option>
        <option value="3">Элитный ремонт квартир</option>
        <option value="4">Дизайн проект квартиры</option>
      </select>
      <label>
        <span>Номер заказа</span>
        <input type="text" name="NZ" value="">
      </label>
      <label>
        <span>Email <b>*</b></span>
        <input type="text" name="email" value="" data-check="true">
      </label>
      <div class="g-recaptcha" data-sitekey="6LcrULwUAAAAAHFi6HnHODyol4wiqudoKVLdjPXn" data-wi="0"></div>
      <button type="button" class="company__bottom-green send-review">Отправить</button>
    </form>
  </div>
  <div class="modal__window window__comment">
    <h3>Коментировать отзыв<span class="close"></span></h3>
    <div class="modal__error"></div>
    <form class="modal__item" id="comment">
      <input type="hidden" name="id" value="" data-check="true"/>
      <input type="hidden" name="key" value="" data-check="true"/>
      <input type="hidden" name="type" value="" data-check="true"/>
      <label>
        <span>Имя <b>*</b></span>
        <input type="text" name="fio" value="" data-check="true">
      </label>
      <label>
        <span>Комментарий <b>*</b></span>
        <textarea name="comment" data-check="true"></textarea>
      </label>
      <div class="g-recaptcha" data-sitekey="6LcrULwUAAAAAHFi6HnHODyol4wiqudoKVLdjPXn" data-wi="1"></div>
      <button type="button" class="company__bottom-green send-comment">Отправить</button>
    </form>
  </div>
  <div class="modal__window window__request">
    <h3>Оставить заявку<span class="close"></span></h3>
    <div class="modal__error"></div>
    <form class="modal__item" id="request">
      <input type="hidden" name="id" value="" data-check="true"/>
      <input type="hidden" name="key" value="" data-check="true"/>
      <input type="hidden" name="type" value="" data-check="true">
      <label>
        <span>Имя <b>*</b></span>
        <input type="text" name="fio" value="" data-check="true">
      </label>
      <label>
        <span>Телефон <b>*</b></span>
        <input type="text" name="phone" value="" placeholder="+7(___) ___-__-__" data-check="true">
      </label>
      <div class="g-recaptcha" data-sitekey="6LcrULwUAAAAAHFi6HnHODyol4wiqudoKVLdjPXn" data-wi="2"></div>
      <button type="button" class="company__bottom-green send-request">Отправить</button>
    </form>
  </div>
  <div class="modal__window window__review__hr">
    <h3>Оставить отзыв<span class="close"></span></h3>
    <div class="modal__error"></div>
    <form class="modal__item" id="review__hr">
      <input type="hidden" name="id" value="" data-check="true"/>
      <input type="hidden" name="key" value="" data-check="true"/>
      <label>
        <span>Должность <b>*</b></span>
        <input type="text" name="position" value="" data-check="true">
      </label>
      <label>
        <span>Что вам понравилось в данной компании? <b>*</b></span>
        <textarea name="review-positiv"></textarea>
      </label>
      <label>
        <span>Что вам не понравилось? <b>*</b></span>
        <textarea name="review-neg"></textarea>
      </label>
      <div class="modal__like">
        <span data-like="1">Рекомендую</span>
        <span data-like="2">Не рекомендую</span>
      </div>
      <div class="g-recaptcha" data-sitekey="6LcrULwUAAAAAHFi6HnHODyol4wiqudoKVLdjPXn" data-wi="3"></div>
      <button class="company__bottom-green send-reviewhr">Отправить</button>
    </form>
  </div>
</div>
