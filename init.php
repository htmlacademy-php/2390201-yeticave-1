<?php
// init.php - Подключение функций, инициализация переменных для работы сайта

// Подключаем дополнительные функции, специально написанные для курса
require_once 'helpers.php';

// Подключаем наши константы
require_once 'constants.php';

// Подключаем самостоятельно написанные функции
require_once 'functions.php';

//Установление SQL-соединения с базой данных yeticave
$db = require_once 'db.php';
$connection = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($connection, "utf8mb4");

if ($connection === false) {
  http_response_code(500);
  die("Ошибка подключения к базе данных.");
}

// Данные, которыми наполняем сайт
$title = 'YetiCave - Главная';
// $is_auth = rand(0, 1);
$is_auth = 0;
$user_name = 'Роман Носков'; // укажите здесь ваше имя
$timer_finishing_hours = 0; // Количество часов, при котором нужно включать модификатор истечения времени лота

