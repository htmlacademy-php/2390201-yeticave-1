
<?php
// Устанавливаем текущую временную зону "Москва GMT+3" для правильной работы с датами
date_default_timezone_set('Europe/Moscow');

// Функция get_dt_range принимает в качестве параметра дату в формате ГГГГ-ММ-ДД,
// и возвращает массив строк, где первый элемент — целое количество часов до даты,
// а второй — остаток в минутах.
function get_dt_range(string $expire_date) {
  $time_left = ['00', '00'];

  $current_time = time();
  $expire_time = strtotime($expire_date);

  $minutes_difference = floor(($expire_time - $current_time) / 60);
  if ($minutes_difference > 0) {
    $time_left[0] = str_pad(strval(floor($minutes_difference / 60)), 2, '0', STR_PAD_LEFT);
    $time_left[1] = str_pad(strval($minutes_difference % 60), 2, '0', STR_PAD_LEFT);
  }
  return $time_left;
}
