<div class="container">
  <section class="lots">
    <h2>Все лоты в категории «<span><?=$category_name;?></span>»</h2>
    <ul class="lots__list">
      <?php if(!$lots_finded): ?>
        <p>В этой категории нет лотов.</p>
      <?php else: ?>
        <?php foreach($lots_finded as $lot):?>
          <li class="lots__item lot">
            <div class="lot__image">
              <img src="<?= $lot['image'];?>" width="350" height="260" alt="<?= $lot['name'];?>">
            </div>
            <div class="lot__info">
              <span class="lot__category"><?= $lot['category'];?></span>
              <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= $lot['id'];?>"><?= $lot['name'];?></a></h3>
              <div class="lot__state">
                <div class="lot__rate">
                  <?php if($lot['bets_number'] === 0): ?>
                    <span class="lot__amount">Стартовая цена</span>
                    <span class="lot__cost"><?= number_format(ceil($lot['start_price']), 0, ',', ' ').'₽';?></span>
                  <?php else: ?>
                    <span class="lot__amount"><?=strval($lot['bets_number'])?> ставок</span>
                    <span class="lot__cost"><?= number_format(ceil($lot['current_price']), 0, ',', ' ').'₽';?></span>
                  <?php endif;?>
                </div>
                <?php
                  $left = get_dt_range($lot['expire_date']);
                  $tfin = (intval($left[0]) < TIMER_FINISING_HOURS) ? ' timer--finishing' : '';
                ?>
                <div class="lot__timer timer<?=$tfin;?>"><?=$left[0].':'.$left[1]?></div>
              </div>
            </div>
          </li>
        <?php endforeach;?>
      <?php endif; ?>
    </ul>
  </section>

  <?php if($pages_number > 1): ?>
    <ul class="pagination-list">
      <li class="pagination-item pagination-item-prev">
        <a <?=($current_page != 1) ? 'href="all-lots.php?category='.$category_id.'&page='.($current_page-1).'"' : '';?>>Назад</a>
      </li>
      <?php for($page = 1; $page <= $pages_number; $page++): ?>
        <li class="pagination-item <?= ($page == $current_page) ? ' pagination-item-active' : '';?>">
          <a href="all-lots.php?category=<?=$category_id?>&page=<?=$page;?>"><?=$page;?></a>
        </li>
      <?php endfor; ?>
      <li class="pagination-item pagination-item-next">
        <a <?=($current_page != $pages_number) ? 'href="all-lots.php?category='.$category_id.'&page='.($current_page+1).'"' : '';?>>Вперед</a>
      </li>
    </ul>
  <?php endif; ?>
</div>
