<?php
// init.php - Подключение функций, инициализация переменных для работы сайта
session_start();

// Подключаем сторонние библиотеки, закгруженные через composer
require_once 'vendor/autoload.php';

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

