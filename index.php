<?php
// Подключаем дополнительные функции, специально написанные для курса
require_once('helpers.php');

// Данные, которыми наполняем сайт
$title = 'YetiCave - Главная';
$is_auth = rand(0, 1);
$user_name = 'Роман Носков'; // укажите здесь ваше имя
$categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];
$lots = [
  [
    'name' => '014 Rossignol District Snowboard',
    'category' => 'Доски и лыжи',
    'price' => 10999,
    'picture' => './img/lot-1.jpg',
    'expire_date' => '2025-06-07'
  ],
  [
    'name' => 'DC Ply Mens 2016/2017 Snowboard',
    'category' => 'Доски и лыжи',
    'price' => 159999,
    'picture' => './img/lot-2.jpg',
    'expire_date' => '2025-06-09'
  ],
  [
    'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
    'category' => 'Крепления',
    'price' => 8000,
    'picture' => './img/lot-3.jpg',
    'expire_date' => '2025-06-10'
  ],
  [
    'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
    'category' => 'Ботинки',
    'price' => 10999,
    'picture' => './img/lot-4.jpg',
    'expire_date' => '2025-06-11'
  ],
  [
    'name' => 'Куртка для сноуборда DC Mutiny Charocal',
    'category' => 'Одежда',
    'price' => 7500,
    'picture' => './img/lot-5.jpg',
    'expire_date' => '2025-06-08'
  ],
  [
    'name' => 'Маска Oakley Canopy',
    'category' => 'Разное',
    'price' => 5400,
    'picture' => './img/lot-6.jpg',
    'expire_date' => '2025-06-10'
  ]
];

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

// Количество часов, при котором нужно включать модификатор истечения времени лота
$timer_finishing_hours = 0;

// Фильтрация $categories, $lots и $user_name для защиты от XSS - удаляем все теги.
foreach($categories as $key => $value) {
  $categories[$key] = strip_tags($value);
}

foreach($lots as $key => $value) {
  foreach($lots[$key] as $lot_key => $lot_value) {
    $lots[$key][$lot_key] = strip_tags($lot_value);
  }
}

$user_name = strip_tags($user_name);

// HTML-код тега <main> главной страницы
$page_content = include_template('main.php', ['categories' => $categories, 'lots' => $lots, 'timer_finishing_hours' => $timer_finishing_hours]);

// окончательный HTML-код
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name, 'categories' => $categories]);

print($layout_content);
?>
