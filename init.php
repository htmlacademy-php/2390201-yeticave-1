<?php
// init.php - Подключение функций, инициализация переменных для работы сайта

// Подключаем дополнительные функции, специально написанные для курса
require_once 'helpers.php';

// Подключаем самостоятельно написанные функции
require_once 'functions.php';

//Установление SQL-соединения с базой данных yeticave
$db = require_once 'db.php';
$connection = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($connection, "utf8mb4");

// Данные, которыми наполняем сайт
$title = 'YetiCave - Главная';
$is_auth = rand(0, 1);
$user_name = 'Роман Носков'; // укажите здесь ваше имя
$timer_finishing_hours = 0; // Количество часов, при котором нужно включать модификатор истечения времени лота

$categories = [
  [
    'id' => 0,
    'name' => '',
    'code' => ''
  ]
];

$lots = [
  [
    'id' => 0,
    'name' => '',
    'description' => '',
    'image' => '',
    'start_price' => 0,
    'expire_date' => '',
    'bet_step' => 0,
    'author_id' => 0,
    'winner_id' => 0,
    'category_id' => 0,
    'category' => ''
  ]
];

