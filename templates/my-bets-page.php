<section class="rates container">
  <h2>Мои ставки</h2>
  <table class="rates__list">
    <?php if(!$my_bets):?>
      <p>Вы не сделали ни одной ставки на наших аукционах.</p>
    <?php else:?>
      <?php foreach($my_bets as $bet):?>
        <tr class="rates__item
          <?=($bet['win']) ? ' rates__item--win' : ''?>
          <?=($bet['expired'] && !$bet['win']) ? ' rates__item--end' : ''?>">

          <td class="rates__info">
            <div class="rates__img">
              <img src="<?=$bet['image'];?>" width="54" height="40" alt="<?=$bet['category'];?>">
            </div>
            <div>
              <h3 class="rates__title">
                <a href="lot.php?id=<?=$bet['lot_id'];?>"><?=$bet['lot_name'];?></a>
              </h3>
              <?=($bet['win']) ? '<p>'.$bet['author_contacts'].'</p>' : ''?>
            </div>
          </td>

          <td class="rates__category">
            <?=$bet['category'];?>
          </td>

          <td class="rates__timer">
            <?php if($bet['win']):?>
              <div class="timer timer--win">Ставка выиграла</div>
            <?php elseif($bet['expired']):?>
              <div class="timer timer--end">Торги окончены</div>
            <?php else:?>
              <?php
                $left = get_dt_range($bet['expire_date']);
                $tfin = (intval($left[0]) < TIMER_FINISING_HOURS) ? ' timer--finishing' : '';
              ?>
              <div class="timer<?=$tfin;?>"><?=$left[0].':'.$left[1].':'.$left[2]?></div>
            <?php endif;?>
          </td>

          <td class="rates__price">
            <?=$bet['price'];?> р
          </td>

          <td class="rates__time">
            <?=humanTimeDiff($bet['make_time']);?>
          </td>
        </tr>
      <?php endforeach;?>
    <?php endif;?>
  </table>
</section>

