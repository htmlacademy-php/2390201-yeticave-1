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
    'picture' => './img/lot-1.jpg'
  ],
  [
    'name' => 'DC Ply Mens 2016/2017 Snowboard',
    'category' => 'Доски и лыжи',
    'price' => 159999,
    'picture' => './img/lot-2.jpg'
  ],
  [
    'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
    'category' => 'Крепления',
    'price' => 8000,
    'picture' => './img/lot-3.jpg'
  ],
  [
    'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
    'category' => 'Ботинки',
    'price' => 10999,
    'picture' => './img/lot-4.jpg'
  ],
  [
    'name' => 'Куртка для сноуборда DC Mutiny Charocal',
    'category' => 'Одежда',
    'price' => 7500,
    'picture' => './img/lot-5.jpg'
  ],
  [
  'name' => 'Маска Oakley Canopy',
  'category' => 'Разное',
  'price' => 5400,
  'picture' => './img/lot-6.jpg'
  ]
];

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
$page_content = include_template('main.php', ['categories' => $categories, 'lots' => $lots]);

// окончательный HTML-код
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name, 'categories' => $categories]);

print($layout_content);
?>
