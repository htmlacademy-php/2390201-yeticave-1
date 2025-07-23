<section class="lot-item container">
  <h2><?=strip_tags($lot['name']);?></h2>
  <div class="lot-item__content">
    <div class="lot-item__left">
      <div class="lot-item__image">
        <img src="<?=strip_tags($lot['image']);?>" width="730" height="548" alt="Сноуборд">
      </div>
      <p class="lot-item__category">Категория: <span><?=strip_tags($lot['category']);?></span></p>
      <p class="lot-item__description"><?=strip_tags($lot['description']);?></p>
    </div>
    <div class="lot-item__right">
      <div class="lot-item__state">
        <?php if($add_lot_allowed): ?>
          <?php
            $left = get_dt_range($lot['expire_date']);
            $tfin = (intval($left[0]) < TIMER_FINISING_HOURS) ? ' timer--finishing' : '';
          ?>
          <div class="lot-item__timer timer<?=$tfin;?>"><?=$left[0].':'.$left[1]?></div>
          <div class="lot-item__cost-state">
            <div class="lot-item__rate">
              <span class="lot-item__amount">Текущая цена</span>
              <span class="lot-item__cost"><?=strip_tags($lot['price']);?></span>
            </div>
            <div class="lot-item__min-cost">
              Мин. ставка <span><?=strip_tags($lot['min_bet']);?> р</span>
            </div>
          </div>
          <form class="lot-item__form" action="" method="post" autocomplete="off">
            <p class="lot-item__form-item form__item<?=isset($errors['bet_price']) ? ' form__item--invalid' : '';?>">
              <label for="cost">Ваша ставка</label>
              <input id="cost" type="text" name="cost" value="<?=getPostVal('cost');?>">
              <span class="form__error"><?=isset($errors['bet_price']) ? $errors['bet_price'] : '';?></span>
            </p>
            <button type="submit" class="button">Сделать ставку</button>
          </form>
        <?php endif; ?>
      </div>
      <div class="history">
        <?php if(!$lot_bets): ?>
          <h3>Ставок по лоту не было</h3>
        <?php else: ?>
          <h3>История ставок (<span><?=count($lot_bets)?></span>)</h3>
          <table class="history__list">
            <?php foreach($lot_bets as $bet):?>
              <tr class="history__item">
                <td class="history__name"><?=$bet['user_name'];?></td>
                <td class="history__price"><?=$bet['price'];?> р</td>
                <td class="history__time"><?=humanTimeDiff($bet['make_time']);?></td>
              </tr>
            <?php endforeach;?>
          </table>
        <?php endif;?>
      </div>
    </div>
  </div>
</section>
