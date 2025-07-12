<?php
// Сценарий добавления нового лота

// Подключение функций, инициализация переменных для работы сайта
require_once 'init.php';

/**
 * @var string user_name
 * @var int $is_auth
 * @var mysqli $connection
 */

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

// HTML - код для отображения формы ввода нового лота
$errors = [];
$page_content = include_template('add-lot.php', ['categories' => $categories, 'errors' => $errors]);

// Отрабатываем нажатие кнопки добавления лота
if($_SERVER['REQUEST_METHOD']==='POST') {
  $lot_and_errors = getLotAndErrors($categories);
  $lot = $lot_and_errors[0];
  $errors = $lot_and_errors[1];

  if(count($errors)) {
    $page_content = include_template('add-lot.php', ['categories' => $categories, 'errors' => $errors]);
  } else {
    addLot($connection, $lot);
  }
}

// окончательный HTML-код
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name, 'categories' => $categories]);

print($layout_content);

