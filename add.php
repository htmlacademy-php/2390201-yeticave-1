<?php
// Сценарий добавления нового лота

// Подключение функций, инициализация переменных для работы сайта
require_once 'init.php';

// Страница закрыта для анонимных пользователей
if (!isset($_SESSION['user'])) {
  http_response_code(403);
  die("Страница добавления лота закрыта для анонимных пользователей.");
}

//Чтение перечня категорий
require_once 'model/read_categories.php';

// Подключаем функцию getLotAndErrors заполнения полей лота из массивов $_POST и $_FILES
// и функцию addLot добавления лота в БД
require_once 'model/add_lot.php';

// Отрабатываем нажатие кнопки добавления лота
$errors = [];
if($_SERVER['REQUEST_METHOD']==='POST') {
  $lot_and_errors = getLotAndErrors($categories);
  $lot = $lot_and_errors[0];
  $errors = $lot_and_errors[1];

  if(!count($errors)) {
    addLot($connection, $lot);
  }
}

// HTML - код для отображения формы ввода нового лота
$page_content = include_template('add-lot.php', ['categories' => $categories, 'errors' => $errors]);

// окончательный HTML-код
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'title' => $title, 'categories' => $categories, 'selected_category' => 0]);

print($layout_content);

