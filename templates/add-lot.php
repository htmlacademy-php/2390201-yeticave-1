<form class="form form--add-lot container form--invalid" action="" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
  <h2>Добавление лота</h2>
  <div class="form__container-two">
    <div class="form__item<?=isset($errors['name']) ? ' form__item--invalid' : '';?>"> <!-- form__item--invalid -->
      <label for="lot-name">Наименование <sup>*</sup></label>
      <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?=getPostVal('lot-name');?>">
      <span class="form__error"><?=isset($errors['name']) ? $errors['name'] : '';?></span>
    </div>
    <div class="form__item<?=isset($errors['category']) ? ' form__item--invalid' : '';?>">
      <label for="category">Категория <sup>*</sup></label>
      <select id="category" name="category">
        <option value="">Выберите категорию</option>
        <?php foreach($categories as $category):?>
          <option value="<?=$category['id'];?>"<?=getPostVal('category') === $category['id'] ? ' selected' : '';?>>
            <?=$category['name'];?>
          </option>
        <?php endforeach;?>
      </select>
      <span class="form__error"><?=isset($errors['category']) ? $errors['category'] : '';?></span>
    </div>
  </div>
  <div class="form__item form__item--wide<?=isset($errors['description']) ? ' form__item--invalid' : '';?>">
    <label for="message">Описание <sup>*</sup></label>
    <textarea id="message" name="message" placeholder="Напишите описание лота"><?=getPostVal('message');?></textarea>
    <span class="form__error"><?=isset($errors['description']) ? $errors['description'] : '';?></span>
  </div>
  <div class="form__item form__item--file<?=isset($errors['image']) ? ' form__item--invalid' : '';?>">
    <label>Изображение <sup>*</sup></label>
    <div class="form__input-file">
      <input class="visually-hidden" type="file" id="lot-img" name="lot-img" value="">
      <label for="lot-img">
        Добавить
      </label>
    </div>
  </div>
  <div class="form__container-three">
    <div class="form__item form__item--small<?=isset($errors['start_price']) ? ' form__item--invalid' : '';?>">
      <label for="lot-rate">Начальная цена <sup>*</sup></label>
      <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?=getPostVal('lot-rate');?>">
      <span class="form__error"><?=isset($errors['start_price']) ? $errors['start_price'] : '';?></span>
    </div>
    <div class="form__item form__item--small<?=isset($errors['bet_step']) ? ' form__item--invalid' : '';?>">
      <label for="lot-step">Шаг ставки <sup>*</sup></label>
      <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?=getPostVal('lot-step');?>">
      <span class="form__error"><?=isset($errors['bet_step']) ? $errors['bet_step'] : '';?></span>
    </div>
    <div class="form__item<?=isset($errors['expire_date']) ? ' form__item--invalid' : '';?>">
      <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
      <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?=getPostVal('lot-date');?>">
      <span class="form__error"><?=isset($errors['expire_date']) ? $errors['expire_date'] : '';?></span>
    </div>
  </div>
  <span class="form__error form__error--bottom">
    <?=count($errors) ? 'Пожалуйста, исправьте ошибки в форме.' : '';?>
    <?=isset($errors['image']) ? $errors['image'] : '';?>
  </span>
  <button type="submit" class="button">Добавить лот</button>
</form>
